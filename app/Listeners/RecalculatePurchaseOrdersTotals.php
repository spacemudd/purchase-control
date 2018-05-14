<?php

namespace App\Listeners;

use App\Events\PurchaseOrderSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecalculatePurchaseOrdersTotals
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PurchaseOrderSaved  $event
     * @return void
     */
    public function handle(PurchaseOrderSaved $event)
    {
        //
    }
}
