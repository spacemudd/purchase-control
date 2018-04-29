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

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Clarimount\Service\PurchaseRequisitionItemsService;

class PurchaseRequisitionItemsController extends Controller
{
    use AuthorizesRequests;

    protected $service;

    public function __construct(PurchaseRequisitionItemsService $service)
    {
        $this->service = $service;
    }

    public function store()
    {
        $this->authorize('create-purchase-requisition-item');

        $data = request()->except('_token');

        return $this->service->store($data);
    }

    public function delete($request_document_id, $id)
    {
        return $this->service->delete($id);
    }

    public function underRequisition($requisition_id)
    {
        return $this->service->underRequisition($requisition_id);
    }

}
