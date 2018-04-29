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
        $data['status'] = PurchaseRequisition::UNSET;

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
            ])
            ->with(['purchase_requisition_items'])
            ->firstOrFail();
    }

    public function lockFind($id)
    {
        return $this->model->where('id', $id)->lockForUpdate()->firstOrFail();
    }

    /**
     * Approves a request.
     *
     * @param $id
     * @return \App\Models\PurchaseRequisition
     */
    public function approve($id)
    {
        $request = DB::transaction(function() use ($id) {

            // The locking.
            $request = $this->model->where('id', $id)->lockForUpdate()->first();

            // Some validation.
            if($request->status != PurchaseRequisition::DRAFT) throw new \Exception('Request must be in draft mode to be approved.');
            if( ! $request->purchase_requisition_items->count()) throw new \Exception('No request items are attached');

            // Calculating the new request number.
            $numberPrefix = 'REQ-' . Carbon::now()->format('Y-m');
            $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                'name' => $numberPrefix,
            ], [
                'value' => 0,
            ]);

            $number = ++$maxNumber->value;

            // The updates.
            $request->number = $maxNumber->name . '-' . sprintf('%05d', $number);
            $request->approved_by_id = auth()->user()->id;
            $request->status = PurchaseRequisition::SAVED;
            $request->save();

            // Save the new number.
            $maxNumber->value = $number;
            $maxNumber->save();

            return $request;
        });

        return $request;
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}