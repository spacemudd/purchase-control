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

use App\Models\Manufacturer;
use Illuminate\Http\Request;

use App\Clarimount\Service\ManufacturerService;

class ManufacturerController extends Controller
{
    protected $service;

    public function __construct(ManufacturerService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view-manufacturers');

        $activeManufacturers = Manufacturer::count();
        $inactiveManufacturers = Manufacturer::onlyTrashed()->count();

        return view('manufacturers.index', compact('activeManufacturers', 'inactiveManufacturers'));
    }

    public function all()
    {
        $this->authorize('view-manufacturers');

        $manufacturers = Manufacturer::orderBy('name', 'asc')->get();

        return view('manufacturers.all', compact('manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        // return view('manufacturers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create-manufacturers');

        $this->service->store();

        session()->flash('status', 'success');
        session()->flash('message', trans('statements.successfully-saved'));

        return redirect()->route('manufacturers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view-manufacturers');

        $manufacturer = $this->service->show($id);

        return view('manufacturers.show', compact('manufacturer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('update-manufacturers');

        $manufacturer = $this->service->show($id);

        return view('manufacturers.edit', compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($id)
    {
        $this->authorize('update-manufacturers');

        $this->service->update($id);

        return redirect()->route('manufacturers.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete-manufacturers');

        $this->service->destroy($id);

        session()->flash('status', 'success');
        session()->flash('message', trans('statements.successfully-deleted'));
            
        return redirect()->route('manufacturers.index');
    }
}
