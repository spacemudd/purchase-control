<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stockCount = 0;
        $rfqsCount = 0;
        $purchaseOrdersCount = 0;
        $deliveriesCount = 0;

        $lowStockCount = 0;
        $outOfStockCount = 0;

    	return view('dashboard.index', compact('stockCount',
            'rfqsCount',
            'purchaseOrdersCount',
            'deliveriesCount',
            'lowStockCount',
            'outOfStockCount'
            ));
    }
}
