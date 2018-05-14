<?php

namespace App\Listeners;

use App\Clarimount\Service\PurchaseOrderService;
use App\Events\PurchaseOrderSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecalculatePurchaseOrdersTotals
{
    public $poService;

    /**
     * Create the event listener.
     *
     * @param \App\Clarimount\Service\PurchaseOrderService $poService
     */
    public function __construct(PurchaseOrderService $poService)
    {
        $this->poService = $poService;
    }

    /**
     * Handle the event.
     *
     * @param  PurchaseOrderSaved  $event
     * @return void
     */
    public function handle(PurchaseOrderSaved $event)
    {
        $po = $event->purchaseOrder;
        $this->poService->calculateAndSavePurchaseOrderCosts($po->id);
    }
}
