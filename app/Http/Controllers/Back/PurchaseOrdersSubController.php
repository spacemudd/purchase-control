<?php

namespace App\Http\Controllers\Back;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrdersSubController extends Controller
{
    public function create($purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::find($purchase_order_id);

        return view('sub-purchase-orders.create', compact('purchaseOrder'));
    }
}
