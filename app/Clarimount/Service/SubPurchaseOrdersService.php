<?php

namespace App\Clarimount\Service;

use App\Clarimount\Repository\PurchaseOrderRepository;
use App\Models\Address;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubPurchaseOrdersService
{
    protected $poRepository;

    public function __construct(PurchaseOrderRepository $poRepository)
    {
        $this->poRepository = $poRepository;
    }

    /**
     * Stores a new sub-PO.
     *
     * @param array $data
     * @param $po_id
     * @return mixed
     */
    public function store(array $data, $po_id)
    {
        $this->validate($data)->validate();

        $newSubPo = DB::transaction(function() use ($data, $po_id) {

            $mainPo = PurchaseOrder::where('id', $po_id)->sharedLock()->firstOrFail();
            $mainPo->vendor_id === $data['vendor_id'] ?
                $data['type'] = PurchaseOrder::TYPE_MULTI_EMPLOYEES :
                $data['type'] = PurchaseOrder::TYPE_MULTI_VENDORS;

            $data['status'] = PurchaseOrder::NEW;
            $data['purchase_order_main_id'] = $mainPo->id;

            if(isset($data['billing_address_id'])) {
                $data['billing_address_json'] = Address::where('id', $data['billing_address_id'])->firstOrFail();
            }

            if(isset($data['shipping_address_id'])) {
                $data['shipping_address_json'] = Address::where('id', $data['shipping_address_id'])->firstOrFail();
            }

            if(isset($data['vendor_id'])) {
                $data['vendor_json'] = Vendor::where('id', $data['vendor_id'])->firstOrFail();;
            }

            $data['created_by_id'] = auth()->user()->id;

            return PurchaseOrder::create($data);
        });

        return $newSubPo;
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'vendor_id' => 'required|exists:vendors,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'currency' => 'required|string|max:3',
        ]);
    }
}
