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
use App\Models\ItemCategory;
use App\Models\ItemTemplate;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use DB;

class ItemTemplateController extends Controller
{
    public function index()
    {
        $activeAssetTemplatesCounter = ItemTemplate::where('active', true)->count();
        $disabledAssetTemplatesCounter = ItemTemplate::where('active', false)->count();

        $activeAssetsCounter = Item::where('active', true)->count();
        $disabledAssetsCounter =  Item::where('active', false)->count();

        return view('settings.asset-templates.index', compact(
            'activeAssetTemplatesCounter',
            'disabledAssetTemplatesCounter',
            'activeAssetsCounter',
            'disabledAssetsCounter'
            ));
    }

    public function create()
    {
        $manufacturers = Manufacturer::active()->get();

        $categories = ItemCategory::orderBy('left', 'asc')->get();

        return view('settings.asset-templates.create', compact('manufacturers', 'categories'));
    }

    public function show($id)
    {
        $assetTemplate = ItemTemplate::with('manufacturer')
            ->with('category')
            ->with('type')
            ->findOrFail($id);

        return view('settings.asset-templates.show', compact('assetTemplate'));
    }
}
