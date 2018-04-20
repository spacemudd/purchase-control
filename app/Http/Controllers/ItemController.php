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

use Illuminate\Http\Request;

use App\Clarimount\Service\ItemService;
use App\Clarimount\Service\ManufacturerService;
use App\Models\Manufacturer;
use App\Models\Item;

class ItemController extends Controller
{
    protected $service;
    protected $manufacturer_service;

    public function __construct(ItemService $service,
                                ManufacturerService $manufacturer_service)
    {
        $this->service = $service;
        $this->manufacturer_service = $manufacturer_service;
    }

    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeItems = Item::count();
        $inactiveItems = Item::onlyTrashed()->count();

        return view('items.index', compact('activeItems', 'inactiveItems'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byPage()
    {
        return view('items.by-page');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $manufacturers = Manufacturer::active()->get();

        $categories = $this->service->categories();

        return view('items.create', compact('manufacturers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->service->store();

        session()->flash('status', 'success');
        session()->flash('message', trans('statements.successfully-saved'));

        return redirect()->route('items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset = Item::where('id', $id)->firstOrFail();

        return view('items.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asset = $this->service->show($id);

        $manufacturers = $this->manufacturer_service->index();
        $categories = $this->service->categories();

        return view('items.edit', compact('asset', 'manufacturers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->service->update($id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->service->destroy($id);

        return redirect()->route('items.index');
    }
}
