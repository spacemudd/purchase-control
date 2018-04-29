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

use App\Models\MaxNumber;
use App\Models\RequestDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\PurchaseRequisitionsRepository;

class PurchaseRequisitionsService
{
    protected $repository;

    public function __construct(PurchaseRequisitionsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store($data)
    {
        $this->validate($data)->validate();

        $data['created_by_id'] = auth()->user()->id;

        return $this->repository->store($data);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'requested_by_id' => 'required|exists:employees,id',
            'cost_center_by_id' => 'required|exists:departments,id',
            'requested_for_id' => 'nullable|exists:employees,id',
            'cost_center_for_id' => 'nullable|exists:departments,id',
        ]);
    }

    /**
     * Approves a request.
     *
     * @param $id
     * @return \App\Models\RequestDocument
     */
    public function approve($id)
    {
        return $this->repository->approve($id);
    }

    /**
     *
     * @param $id Request ID
     */
    public function sendToPurchasing($id)
    {
        $request = $this->repository->find($id);

        if($request->status != RequestDocument::UNSET) return abort(404);

        $request->status = RequestDocument::DRAFT;
        $request->save();

        // todo: send a notification.

        return $request;
    }

    public function delete($id)
    {
        if( ! $this->repository->find($id)->canAddItems) throw new \Exception('Requisition must be in draft mode.');

        return $this->repository->delete($id);
    }

    /**
     * Saves the purchase requisition (to become unmodifiable) for rejection/approval.
     *
     * @param $id
     * @return mixed
     */
    public function save($id)
    {
        $requisition = DB::transaction(function() use ($id) {
            $requisition = $this->repository->lockFind($id);

            if($requisition->status != (RequestDocument::DRAFT || RequestDocument::UNSET)) throw new \Exception('Requisition must be in draft mode');

            // Calculating the new request number.
            $numberPrefix = 'REQ-' . Carbon::now()->format('Y-m');
            $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                'name' => $numberPrefix,
            ], [
                'value' => 0,
            ]);

            $number = ++$maxNumber->value;

            // The updates.
            $requisition->number = $maxNumber->name . '-' . sprintf('%05d', $number);

            $requisition->status = RequestDocument::SAVED;
            $requisition->save();

            // Save the new number.
            $maxNumber->value = $number;
            $maxNumber->save();

            return $requisition;
        }, 2);

        // todo: notification.

        return $requisition;
    }
}
