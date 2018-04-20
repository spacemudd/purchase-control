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
        $assetCount = Item::count();
        $purchaseOrdersCount = PurchaseOrder::count();
        // $assetStorageCount = Asset::where('status', 'active')->count(); TODO: This will return an error because the Status column was removed.

    	return view('dashboard.index', compact('assetCount', 'purchaseOrdersCount'
            ));
    }
}
