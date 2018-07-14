<?php

namespace App\Clarimount\Service;

use App\Clarimount\Repository\PurchaseOrderRepository;
use App\Models\Address;
use App\Models\MaxNumber;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubPurchaseOrdersService
{
    protected $poRepository;
    protected $poService;

    public function __construct(PurchaseOrderRepository $poRepository, PurchaseOrderService $poService)
    {
        $this->poRepository = $poRepository;
        $this->poService = $poService;
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

            $data['status'] = PurchaseOrder::NEW;
            $data['date'] = now();
            $data['purchase_order_main_id'] = $mainPo->id;

            if(isset($data['billing_address_id'])) {
                $data['billing_address_json'] = Address::where('id', $data['billing_address_id'])->firstOrFail();
            } else {
                $firstBillingAddress = Address::billing()->first();
                if($firstBillingAddress) {
                    $data['billing_address_id'] = $firstBillingAddress->id;
                    $data['billing_address_json'] = $firstBillingAddress;
                }
            }

            if(isset($data['shipping_address_id'])) {
                $data['shipping_address_json'] = Address::where('id', $data['shipping_address_id'])->firstOrFail();
            } else {
                $shippingAddress = Address::shipping()->first();
                if($shippingAddress) {
                    $data['shipping_address_id'] = $shippingAddress->id;
                    $data['shipping_address_json'] = $shippingAddress;
                }
            }

            if(isset($data['vendor_id'])) {
                $data['vendor_json'] = Vendor::where('id', $data['vendor_id'])->firstOrFail();;
            }

            $data['created_by_id'] = auth()->user()->id;

            $po = PurchaseOrder::create($data);

            $this->poService->saveTermsToPo($po->id);

            return $po;
        });

        return $newSubPo;
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'vendor_id' => 'nullable|exists:vendors,id',
            'shipping_address_id' => 'nullable|exists:addresses,id',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'currency' => 'required|string|max:3',
        ]);
    }

    /**
     * Saves & generates an ID for the sub purchase order.
     *
     * If multi-vendors, it appends '-A' and increments the lettering.
     * If multi-requests, it appends '-1' and increments.
     *
     * @param $id
     * @return mixed
     */
    public function save($id)
    {
        Log::info('saving po id:'.$id);
        $subPo = DB::transaction(function() use ($id) {
            Log::info('[Saving] Attempting to save SubPO ID: '. $id);
            $subPo = PurchaseOrder::where('id', $id)->sharedLock()->firstOrFail();
            $mainPo = PurchaseOrder::where('id', $subPo->purchase_order_main_id)->sharedLock()->firstOrFail();

            $mainPo->vendor_id === $subPo->vendor_id ?
                $subPo->update(['type' => PurchaseOrder::TYPE_MULTI_EMPLOYEES]) :
                $subPo->update(['type' => PurchaseOrder::TYPE_MULTI_VENDORS]);

            $subPo->refresh();

            // todo: validation.
            $multiVendor = $this->isPoMultiVendor($subPo);

            if($multiVendor) {
                // Increment the last PO's lettering if available.
                Log::info('[Saving] SubPO ID '.$id.': is multi-vendor');

                if(MaxNumber::where('name', $mainPo->number.'-LETTER')->exists()) {
                    $maxNumber = MaxNumber::where('name', $mainPo->number.'-LETTER')->lockForUpdate()->first();
                    $maxNumber->value = ++$maxNumber->value;
                    $maxNumber->save();
                } else {
                    $maxNumber = MaxNumber::create([
                        'name' => $mainPo->number.'-LETTER',
                        'value' => 'A',
                    ]);
                }

                Log::info('[Saving] SubPO ID '.$id.': Assigning number '.$mainPo->number.'-'.$maxNumber->value);
                $subPo->number = $mainPo->number.'-'.$maxNumber->value;

            } else {
                Log::info('[Saving] SubPO ID: '.$id.' has same vendor as its main PO.');

                if(MaxNumber::where('name', $mainPo->number.'-NUMBER')->exists()) {
                    $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                        'name' => $mainPo->number.'-NUMBER',
                    ], [
                        'value' => 1,
                    ]);

                    Log::info('[Saving] SubPO ID: '.$id.' Found latest number ' . $maxNumber->value);
                    $numbers = ++$maxNumber->value;
                    Log::info('[Saving] SubPO ID: '.$id.' Assigning ' . $numbers);
                } else {
                    $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                        'name' => $mainPo->number.'-NUMBER',
                    ], [
                        'value' => 1,
                    ]);
                    Log::info('[Saving] SubPO ID '.$id.': no other saved subPOs. Assigning '.$maxNumber->value.' to this sub PO.');
                    $numbers =  $maxNumber->value;
                    Log::info('[Saving] SubPO ID: '.$id.' Assigning 1');
                }

                $subPo->number = $mainPo->number.'-'.$numbers;
                Log::info('[Saving] SubPO ID: '.$id.' Final number '.$subPo->number);
            }

            if(PurchaseOrder::where('number', $subPo->number)->exists()) {
                //dd(PurchaseOrder::get()->toArray());
                dd('Exists!'.$subPo->number);
            }

            $subPo->status = PurchaseOrder::SAVED;
            $subPo->save();

            $maxNumber->save();

            Log::info('[Saving] SubPO ID: '.$id.' Saved with number '.$subPo->number);

            return $subPo;
        });

        return $subPo;
    }

    public function isPoMultiVendor($purchaseOrder)
    {
        return $purchaseOrder->vendor_id != $purchaseOrder->main_purchase_order->vendor_id;
    }
}
