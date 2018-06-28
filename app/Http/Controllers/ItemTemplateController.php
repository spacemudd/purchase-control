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

use App\Models\Category;
use App\Models\ItemTemplate;
use App\Models\Manufacturer;
use App\Models\MaxNumber;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemTemplateController extends Controller
{
    public function index()
    {
        $this->authorize('view-item-templates');

        $itemTemplatesCounter = ItemTemplate::count();

        return view('item-templates.index', compact(
            'itemTemplatesCounter'
            ));
    }

    public function create()
    {
        $this->authorize('create-item-templates');

        $manufacturers = Manufacturer::get();
        $categories = Category::get()->toTree();

        return view('item-templates.create', compact('manufacturers', 'categories'));
    }

    public function edit($id)
    {
        $this->authorize('update-item-templates');

        $itemTemplate = ItemTemplate::findOrFail($id);
        $manufacturers = Manufacturer::get();
        $categories = Category::get();

        return view('item-templates.edit', compact('manufacturers', 'categories', 'itemTemplate'));
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update-item-templates');

        $request->validate([
            'code' => 'required|unique:item_templates,code,' . $id,
            'model_number' => 'nullable|string|max:255',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'eol' => 'nullable|numeric',
            'unit_price' => 'nullable|numeric',
        ]);

        if($request->unit_price) {
            $unitPrice = Money::of($request->unit_price, 'SAR')->getAmount()->toInt();
        } else {
            $unitPrice = null;
        }

        $data = $request->except(['_token', '_method']);
        unset($data['unit_price']);
        $data['default_unit_price_minor'] = $unitPrice;

        ItemTemplate::where('id', $id)
            ->update($data);

        return redirect()->route('item-templates.show', ['id' => $id]);
    }

    public function show($id)
    {
        $this->authorize('view-item-templates');

        $itemTemplate = ItemTemplate::with('manufacturer')
            ->findOrFail($id);

        return view('item-templates.show', compact('itemTemplate'));
    }
}
