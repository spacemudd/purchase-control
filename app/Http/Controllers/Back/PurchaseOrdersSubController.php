<?php

namespace App\Http\Controllers\Back;

use App\Clarimount\Service\SubPurchaseOrdersService;
use App\Clarimount\Service\VendorBankService;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PurchaseOrdersSubController extends Controller
{
    protected $service;
    protected $vendorBankService;

    public function __construct(SubPurchaseOrdersService $service, VendorBankService $vendorBankService)
    {
        $this->service = $service;
        $this->vendorBankService = $vendorBankService;
    }

    public function create($purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::find($purchase_order_id);

        $currencies = $this->vendorBankService->currencies();

        return view('sub-purchase-orders.create', compact('purchaseOrder', 'currencies'));
    }

    /**
     * Create a new sub PO.
     *
     * @param $purchase_order_id
     * @return mixed
     */
    public function store($purchase_order_id)
    {
        $data = request()->except('_token');

        $subPo = $this->service->store($data, $purchase_order_id);

        return redirect()->route('purchase-orders.sub.show', ['purchase_order_id' => $purchase_order_id,'id' => $subPo->id]);
    }

    /**
     * Generates a new sub PO number.
     *
     * @param $purchase_order_id
     * @param $id
     * @return mixed
     */
    public function save($purchase_order_id, $id)
    {
        return $this->service->save($id);
    }

    public function show($purchase_order_id, $id)
    {
        $subPurchaseOrder = PurchaseOrder::where('id', $id)
            ->where('purchase_order_main_id', '!=', null)
            ->with('main_purchase_order')
            ->firstOrFail();

        return view('sub-purchase-orders.show', compact('subPurchaseOrder'));
    }
}
