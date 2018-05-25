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

use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Service\PurchaseRequisitionsService;

class PurchaseRequisitionsController extends Controller
{
    protected $service;

    public function __construct(PurchaseRequisitionsService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        return $this->service->store($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function sendToPurchasing($id)
    {
        return $this->service->sendToPurchasing($id);
    }

    public function subscribe($id)
    {
        $requisition = $this->service->find($id);

        $subscribers[] = auth()->user()->id;
        foreach($requisition->subscribers()->get() as $subscriber) {
            $subscribers[] = $subscriber->id;
        }

        $requisition->subscribers()->sync($subscribers);
;
        return response()->json(['status' => 201]);
    }

    public function unsubscribe($id)
    {
        $requisition = $this->service->find($id);

        $subscribers = [];
        foreach($requisition->subscribers()->get() as $subscriber) {
            if( $subscriber->id == auth()->user()->id ) {

            } else {
                $subscribers[] = $subscriber->id;
            }
        }

        $requisition->subscribers()->sync($subscribers);

        return response()->json(['status' => 201]);
    }

    public function approve($id)
    {
        return $this->service->approve($id);
    }

    /**
     * Updates purpose of the request.
     *
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePurpose($id, Request $request)
    {
        $request->validate([
            'purpose' => 'nullable|string|max:255',
        ]);

        $pr = $this->service->find($id);

        if($pr->is_approved) {
            return response()->json(['errors' => [
                'The purchase requisition is already approved',
            ]], 422);
        }

        $pr->purpose = $request->purpose;
        $pr->save();

        return $pr;
    }

    /**
     * Updates remarks of the request.
     *
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRemarks($id, Request $request)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:255',
        ]);

        $pr = $this->service->find($id);

        if($pr->is_approved) {
            return response()->json(['errors' => [
                'The purchase requisition is already approved',
            ]], 422);
        }

        $pr->itam_remarks = $request->remarks;
        $pr->save();

        return $pr;
    }

    /**
     * @return mixed
     */
    public function searchSaved()
    {
        $search = request()->input('q');

        $results = PurchaseRequisition::where('number', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        $results->load('items');

        return $results;
    }
}
