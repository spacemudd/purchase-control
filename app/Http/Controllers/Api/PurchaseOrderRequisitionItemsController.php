<?php

namespace App\Http\Controllers\Api;

use App\Clarimount\Service\PurchaseOrderRequisitionItemsService;
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
     */
    public function store($po_id)
    {
        return $this->service->store($po_id);
    }
}
