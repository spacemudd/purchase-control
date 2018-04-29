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

    public function approve(Request $request)
    {
        $data['id'] = $request->id;

        $this->validateApprove($data)->validate();

        return $this->service->approve($data['id']);
    }

    public function sendToPurchasing($id)
    {
        return $this->service->sendToPurchasing($id);
    }

    public function validateApprove(array $data)
    {
        return Validator::make($data, [
            'id' => 'required|exists:purchase_requisitions',
        ]);
    }
}
