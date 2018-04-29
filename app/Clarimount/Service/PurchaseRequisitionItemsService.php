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

namespace App\Clarimount\Service;

use App\Models\Item;
use App\Models\ItemTemplate;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\PurchaseRequisitionItemsRepository;

class PurchaseRequisitionItemsService
{
    protected $repo;

    public function __construct(PurchaseRequisitionItemsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store($data)
    {
        $this->validate($data)->validate();

        // Fill some of the immutable data.
        $itemTemplate = ItemTemplate::findOrFail($data['item_template_id']);
        $data['code'] = $itemTemplate->code;
        $data['name'] = $itemTemplate->name;
        $data['model_number'] = $itemTemplate->model_number;
        $data['manufacturer'] = optional($itemTemplate->manufacturer)->name;

        return $this->repo->store($data);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'purchase_requisition_id' => 'required|exists:purchase_requisitions,id',
            'item_template_id' => 'required|exists:item_templates,id',
            'qty' => 'required|numeric|min:0',
        ]);
    }

    /**
     * Deletes a request item.
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function delete($id)
    {
        $requisitionItem = $this->repo->find($id);
        if( ! $requisitionItem->purchase_requisition->canAddItems) throw new \Exception('Requisition must be in draft mode.');

        return $this->repo->delete($id);
    }

    /**
     * Shows all the items under a requisition.
     *
     * @param $id Requisition ID.
     * @return mixed
     */
    public function underRequisition($id)
    {
        return $this->repo->underRequisition($id);
    }

}
