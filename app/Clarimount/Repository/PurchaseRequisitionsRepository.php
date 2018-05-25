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

namespace App\Clarimount\Repository;

use App\Models\MaxNumber;
use Carbon\Carbon;
use App\Models\PurchaseRequisition;
use DB;

class PurchaseRequisitionsRepository
{
    protected $model;
    
    public function __construct(PurchaseRequisition $model)
    {
        $this->model = $model;
    }

    /**
     * Storing a draft request.
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->where('id', $id)
            ->with([
                'requested_by',
                'requested_for',
                'cost_center_by',
                'cost_center_for',
                'created_by',
                'approved_by',
                'recommended_by',
            ])
            ->with(['purchase_requisition_items'])
            ->firstOrFail();
    }

    public function lockFind($id)
    {
        return $this->model->where('id', $id)->lockForUpdate()->firstOrFail();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
