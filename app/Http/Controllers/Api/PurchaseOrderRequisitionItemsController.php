<?php

namespace App\Http\Controllers\Api;

use App\Clarimount\Service\PurchaseOrderRequisitionItemsService;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use App\Models\PurchaseRequisitionItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderRequisitionItemsController extends Controller
{
    protected $service;

    public function __construct(PurchaseOrderRequisitionItemsService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a new item to the PO from the Purchase Requisition.
     *
     * @param $po_id
     * @return mixed
     */
    public function store($po_id)
    {
        return $this->service->store($po_id);
    }

    /**
     * Deletes a PO item obtained from a purchase requisition.
     *
     * @param $po_id
     * @param $id
     * @return bool
     */
    public function delete($po_id, $id)
    {
        $item = PurchaseOrdersItem::where('id', $id)->firstOrFail();

        $poNumber = PurchaseOrder::where('id', $po_id)->firstOrFail()->number;
        if($poNumber) abort('PO must be in draft mode');

        // todo: emit recalculate total po cost.
        $item->delete();

        return response()->json(['status' => 200], 200);
    }
}
