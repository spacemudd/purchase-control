<?php

namespace App\Http\Controllers;

use App\Clarimount\Service\VendorRepresentativeService;
use App\Clarimount\Service\VendorService;
use Illuminate\Http\Request;

class VendorRepresentativesController extends Controller
{
    protected $vendorService;
    protected $service;

    public function __construct(VendorService $vendorService, VendorRepresentativeService $service)
    {
        $this->vendorService = $vendorService;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO.
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function create($vendor_id)
    {
        $vendor = $this->vendorService->show($vendor_id);

        return view('vendor-representatives.create', compact('vendor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('create-vendor-representative');

        $rep = $this->service->store();

        return redirect()->route('vendors.show', ['id' => $rep->vendor_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vendor_id, $id)
    {
        // todo: permission.

        $this->service->destroy($id);

        return redirect()->back();
    }
}
