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

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\AssetIssuance;
use App\Models\AssetIssuanceReturn;
use App\Models\Employee;
use App\Models\PurchaseOrder;
use App\Models\RequestDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditsController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'resource_type' => 'required|string|max:255',
            'resource_id' => 'required|numeric',
        ]);

        switch ($request->resource_type) {
            case 'purchase-orders':
                $resource = PurchaseOrder::where('id', $request->resource_id);
                break;
            case 'asset-issuances':
                $resource = AssetIssuance::where('id', $request->resource_id);
                break;
            case 'issuance-returns':
                $resource = AssetIssuanceReturn::where('id', $request->resource_id);
                break;
            case 'employees':
                $resource = Employee::where('id', $request->resource_id);
                break;
            case 'assets':
                $resource = Item::where('id', $request->resource_id);
                break;

            // PurchaseControl module.
            case 'requests':
                $resource = RequestDocument::where('id', $request->resource_id);
                break;
        }

        return $resource->firstOrFail()->audits()->with('user')->latest()->get();
    }
}
