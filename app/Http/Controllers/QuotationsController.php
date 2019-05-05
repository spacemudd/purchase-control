<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\Quotation;
use App\Models\Vendor;
use App\Services\QuotationsService;
use Illuminate\Http\Request;

class QuotationsController extends Controller
{
    protected $service;

    public function __construct(QuotationsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = Quotation::latest()->get();
        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::get();
        $mRequests = MaterialRequest::get();
        return view('quotations.create', compact('vendors', 'mRequests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'material_request_id' => 'required|numeric|exists:material_requests,id',
            'vendor_id' => 'required|numeric|exists:vendors,id',
            'vendor_quotation_number' => 'required|string|max:255',
        ]);

        // TODO: Confirm unique on vendor_id->vendor_quotation_number

        $request['status'] = Quotation::DRAFT;
        $request['cost_center_id'] = MaterialRequest::find($request['material_request_id'])->cost_center_id;
        $quotation = Quotation::create($request->except('_token'));

        return redirect()->route('quotations.show', ['id' => $quotation->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::where('id', $id)->firstOrFail();
        return view('quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
    public function destroy($id)
    {
        //
    }

    /**
     * Change status from 'DRAFT' to 'SAVED.
     * No more changes shall be done to the quotations from now on.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save($id)
    {
        $quote = $this->service->save($id);
        return redirect()->route('quotations.show', ['id' => $quote->id]);
    }
}
