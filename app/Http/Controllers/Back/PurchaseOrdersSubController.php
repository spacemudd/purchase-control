<?php

namespace App\Http\Controllers\Back;

use App\Clarimount\Service\SubPurchaseOrdersService;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;

class PurchaseOrdersSubController extends Controller
{
    protected $service;

    public function __construct(SubPurchaseOrdersService $service)
    {
        $this->service = $service;
    }

    public function create($purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::find($purchase_order_id);

        return view('sub-purchase-orders.create', compact('purchaseOrder'));
    }

    /**
     * Create a new sub PO.
     *
     * @param $purchase_order_id
     */
    public function store($purchase_order_id)
    {
        $data = request()->except('_token');

        return $this->service->store($data, $purchase_order_id);
    }
}
