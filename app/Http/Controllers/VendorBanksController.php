<?php

namespace App\Http\Controllers;

use App\Clarimount\Service\VendorBankService;

class VendorBanksController extends Controller
{
    protected $service;

    public function __construct(VendorBankService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create-vendor-bank');

        return view('vendor-banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('create-vendor-bank');

        $vendorBank = $this->service->store();

        return redirect()->route('vendors.show', ['id' => $vendorBank->vendor_id]);
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
        $this->authorize('update-vendor-bank');

        $vendorBank = $this->service->update($id);

        return redirect()->route('vendors.show', ['id' => $vendorBank->vendor_id]);
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
        $this->authorize('delete-vendor-bank');

        $this->service->destroy($id);

        return redirect()->route('vendors.index');
    }
}
