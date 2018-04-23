<?php

namespace App\Http\Controllers;

use App\Clarimount\Service\VendorBankService;
use App\Clarimount\Service\VendorService;

class VendorBankController extends Controller
{
    protected $service;
    protected $vendorService;

    public function __construct(VendorBankService $service, VendorService $vendorService)
    {
        $this->service = $service;
        $this->vendorService = $vendorService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $vendor_id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($vendor_id)
    {
        $this->authorize('create-vendor-bank');

        $vendor = $this->vendorService->show($vendor_id);

        $currencies = $this->service->currencies();

        if($vendor->bank) return abort('404');

        return view('vendor-bank.create', compact('vendor', 'currencies'));
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

        $vendor = $this->vendorService->show(request()->input('vendor_id'));
        if($vendor->bank) return abort(404);

        $vendorBank = $this->service->store();

        return redirect()->route('vendors.show', ['id' => $vendorBank->vendor_id]);
    }

    /**
     * Show the form for updating a new resource.
     *
     * @param $vendor_id
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($vendor_id, $id)
    {
        $this->authorize('update-vendor-bank');

        $vendorBank = $this->service->show($id);

        $currencies = $this->service->currencies();

        return view('vendor-bank.edit', compact('vendorBank', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($vendor_id, $id)
    {
        $this->authorize('update-vendor-bank');

        $this->service->update($id);

        return redirect()->route('vendors.show', ['id' => $vendor_id]);
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
