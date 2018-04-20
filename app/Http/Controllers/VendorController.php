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

use App\Models\Vendor;
use Illuminate\Http\Request;

use App\Clarimount\Service\VendorService;

class VendorController extends Controller
{
	protected $service;

	public function __construct(VendorService $service)
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
	    $this->authorize('view-vendor');

        $activeVendors = Vendor::count();
        $inactiveVendors = Vendor::onlyTrashed()->count();

		return view('vendors.index', compact('activeVendors', 'inactiveVendors'));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function create()
	{
	    $this->authorize('create-vendor');

		return view('vendors.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$vendor = $this->service->store();

		return redirect()->route('vendor.show', ['id' => $vendor->id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
	    $vendor = Vendor::findOrFail($id);

		return view('vendors.show', compact('vendor'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
        $vendor = $this->service->show($id);
		return view('vendors.edit', compact('vendor'));
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
        $this->authorize('update-vendor');

		$this->service->update($id);

		return redirect()->route('vendors.show', ['id' => $id]);
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
	    $this->authorize('delete-vendor');

		$this->service->destroy($id);

		return redirect()->route('vendors.index');
	}
}
