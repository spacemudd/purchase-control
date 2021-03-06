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

use App\Models\Employee;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Clarimount\Service\PurchaseRequisitionsService;
use App\Models\PurchaseRequisition;

class PurchaseRequisitionsController extends Controller
{
    protected $service;

    public function __construct(PurchaseRequisitionsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $draftCounter = PurchaseRequisition::where('status', PurchaseRequisition::DRAFT)->count();
        $savedCounter = PurchaseRequisition::where('status', PurchaseRequisition::SAVED)->count();
        $approvedCounter = PurchaseRequisition::where('status', PurchaseRequisition::VOID)->count();

        return view('purchase-requisitions.index', compact('draftCounter', 'savedCounter', 'approvedCounter'));
    }

    /**
     *
     * @param string $statusSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paginatedByStatus(string $statusSlug)
    {
        $records = PurchaseRequisition::query();

        switch ($statusSlug) {
            case 'draft':
                $records->draft();
                break;

            case 'saved':
                $records->saved();
                break;

            case 'void':
                $records->void();
                break;

            default:
                abort(404);
        }

        $data = $records->latest()->paginate(50);
        return view('purchase-requisitions.paginated-by-status', compact('data', 'statusSlug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $employees = Employee::get();
        return view('purchase-requisitions.create', compact('employees'));
    }

    /**
     * Show the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $request = $this->service->find($id);

        return view('purchase-requisitions.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return void
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->service->delete($id);

        if(request()->wantsJson()) {
            return [
                'status' => true,
                'redirect' => route('purchase-requisitions.index'),
            ];
        }

        return redirect()->route('purchase-requisitions.index');
    }

    /**
     * Store a newly created resource.
     *
     * @return Response
     */
    public function store()
    {
        $data = request()->except('_token');

        $purchaseRequisition = $this->service->store($data);

        return redirect()->route('purchase-requisitions.show', ['id' => $purchaseRequisition->id]);
    }

    /**
     * Saves the purchase requisition (to become unmodifiable) for rejection/approval.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save($id)
    {
        if(! $this->service->find($id)->purchase_requisition_items()->count() ) {
            session()->flash('status', 'is-warning');
            session()->flash('messages', [
                'The purchase requisitions doesnt have any items',
            ]);
            return redirect()->back();
        }

        $requisition = $this->service->save($id);

        return redirect()->route('purchase-requisitions.show', ['id' => $requisition->id]);
    }

    /**
     * Print a PDF form of the requisition.
     *
     * @param $id
     * @return \Knp\Snappy\Pdf
     * @throws \Exception
     */
    public function pdf($id)
    {
        $pdf = $this->service->pdf($id);

        return $pdf->inline();
    }
}
