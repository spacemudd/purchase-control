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

use App\Models\RequestDocumentItem;

class PurchaseRequisitionItemsRepository
{
    protected $model;

    public function __construct(RequestDocumentItem $model)
    {
        $this->model = $model;
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     *
     * @param $id
     * @return mixed
     */
    public function underRequisition($id)
    {
        return $this->model->where('request_document_id', $id)->get();
    }
}
