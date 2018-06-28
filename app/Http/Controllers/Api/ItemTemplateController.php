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

use App\Clarimount\Service\ItemTemplateService;
use App\Models\AssetConfiguration;
use App\Models\AssetStatus;
use App\Models\ItemTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemTemplateController extends Controller
{

    protected $service;

    public function __construct(ItemTemplateService $service)
    {
        $this->service = $service;
    }

    public function all()
    {
        $stockStatus = AssetStatus::where('name', 'In Stocks')->first();

        $assetTemplates = ItemTemplate::withCount(['assets' => function($q) use ($stockStatus) {
            $q->where('asset_status_id', $stockStatus->id);
        }])
            ->with('manufacturer')
            ->get()
            ->toArray();

        return $assetTemplates;
    }

    public function show(Request $request)
    {
        $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,id'
        ]);

        $stockStatus = AssetStatus::where('name', 'In Stocks')->first();

        return ItemTemplate::where('id', $request['asset_template_id'])
            ->with('manufacturer')
            ->with('type')
            ->with(['in_stock_assets' => function($q) use ($stockStatus) {
                $q->with('purchase_order')->where('asset_status_id', $stockStatus->id);
            }])
            ->firstOrFail();
    }

    public function store()
    {
        $this->authorize('create-item-templates');

        $request = request()->except('_token');

        $data = $this->service->store($request);

        return response()->json([
            'data' => $data,
            'redirect' => route('item-templates.show', ['id' => $data->id]),
        ]);
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $results = ItemTemplate::where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->orWhere('model_number', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return $results;
    }

    /**
     * This shows the configurations of a specific asset.
     *
     * @param $asset_template_id
     * @return
     */
    public function showConfigurations($asset_template_id)
    {
        return AssetConfiguration::where('asset_template_id', $asset_template_id)->get();
    }

    public function storeConfigurations($asset_template_id, Request $request)
    {
        $assetTemplate = ItemTemplate::where('id', $asset_template_id)->firstOrFail();

        $request->validate([
            'property' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $configuration = new AssetConfiguration();
        $configuration->asset_template_id = $assetTemplate->id;
        $configuration->property = $request->property;
        $configuration->value = $request->value;
        $configuration->save();

        return $configuration;
    }

    public function updateConfigurations($asset_template_id, Request $request)
    {
        $request->validate([
            'id' => 'required|exists:asset_configurations,id',
            'property' => 'required|string|max:255|min:1',
            'value' => 'required|string|max:255|min:1',
        ]);

        return AssetConfiguration::where('id', $request->id)->update([
            'property' => $request->property,
            'value' => $request->value,
        ]);
    }
}
