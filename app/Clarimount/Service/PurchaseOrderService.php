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

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Clarimount\Repository\PurchaseOrderRepository;

use App\Models\PurchaseOrder;

class PurchaseOrderService
{
	protected $repository;

	public function __construct(PurchaseOrderRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index()
	{
		return $this->repository->all();
	}

	public function paginatedIndex($per_page)
	{
		if($per_page > 100) {
			$per_page = 100;
		}

		if($per_page < 10) {
			$per_page = 10;
		}

		return $this->repository->paginatedIndex($per_page);
	}

	public function destroy($id)
	{
		$this->failIfNotDraft($id);
		return $this->repository->destroy($id);
	}

	/**
	 * Gives 404 if the purchase order is committed.
	 * @param  int $id purchase order id
	 * @return \Illuminate\Http\Response
	 */
	public function failIfNotDraft($id)
	{
		$status = PurchaseOrder::where('id', $id)->firstOrFail()->status;

		if($status != 'draft') {
			return abort(404);
		}
	}

	public function store()
	{
		$purchase_order = request()->except(['_token']);

		$this->validate($purchase_order)->validate();

        if($purchase_order['date'] && array_key_exists('date', $purchase_order)) {
            $purchase_order['date'] = Carbon::createFromFormat('d/m/Y', $purchase_order['date']);
        }
        if($purchase_order['delivery_date'] && array_key_exists('delivery_date', $purchase_order)) {
            $purchase_order['delivery_date'] = Carbon::createFromFormat('d/m/Y', $purchase_order['delivery_date']);
        }

        $purchase_order['status'] = PurchaseOrder::NEW;

		return $this->repository->create($purchase_order);
	}

    public function attachments()
    {
        $request = request()->only('id');

        return $this->repository->attachments($request['id']);
    }

    public function downloadAttachment()
    {
        $request = request()->only('id');

        return $this->repository->downloadAttachment($request['id']);
    }

	public function edit($id)
	{
		return $this->repository->find($id);
	}

	public function update($id, array $purchaseOrder)
	{
		$this->validate($purchaseOrder)->validate();

		if(array_key_exists('date', $purchaseOrder) && $purchaseOrder['date']) {
            $purchaseOrder['date'] = Carbon::createFromFormat('d/m/Y', $purchaseOrder['date']);
        }

        if(array_key_exists('delivery_date', $purchaseOrder) && $purchaseOrder['delivery_date']) {
            $purchaseOrder['delivery_date'] = Carbon::createFromFormat('d/m/Y', $purchaseOrder['delivery_date']);
        }

		return $this->repository->update($id, $purchaseOrder);
	}

	public function show($id)
	{
		return $this->repository->find($id);
	}

	public function commit()
	{
		$purchase_order = request()->only('id');

		$this->validatePoExists($purchase_order)->validate();
		
		if( ! $this->isPoDraft($purchase_order['id'])) {
			return false;
		}

		return $this->repository->commit($purchase_order['id']);
	}

	public function void()
	{
		$purchase_order = request()->only('id');

		$this->validatePoExists($purchase_order)->validate();
		
		if( ! $this->isPoCommitted($purchase_order['id'])) {
			return false;
		}

		return $this->repository->void($purchase_order['id']);
	}

	public function validate(array $data)
	{
		$validator = Validator::make($data, [
		    'date' => 'required|max:255',
            'vendor_id' => 'required|exists:vendors,id',
            'delivery_number' => 'nullable|string|max:255',
            'main_order_number' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|exists:employees,id',
            'delivery_date' => 'nullable',
        ]);


		$validator->after(function($validator) use ($data) {

		    try {
		        if($data['date'] && array_key_exists('date', $data)) {
                    $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);
                }
            } catch(\Exception $error) {
                $validator->errors()->add('date', trans('validation.date', ['attribute' => 'date']));
            }

            try {
                if($data['delivery_date'] && array_key_exists('delivery_date', $data)) {
                    $date['delivery_date'] = Carbon::createFromFormat('d/m/Y', $data['delivery_date']);
                }
            } catch(\Exception $error) {
                $validator->errors()->add('delivery_date', trans('validation.date', ['attribute' => 'date']));
            }

        });

		return $validator;
	}

	public function validatePoExists(array $data)
	{
		return Validator::make($data, ['id' => 'required|exists:purchase_orders,id']);
	}

	/**
	 * Confirms Purchase Order is in draft status.
	 *
	 * @param  int  $id
	 * @return boolean
	 */
	public function isPoDraft($id)
	{
		return PurchaseOrder::where('id', $id)->firstOrFail()->isDraft;
	}

	/**
	 * Confirms Purchase Order is in void status.
	 *
	 * @param  int  $id
	 * @return boolean
	 */
	public function isPoCommitted($id)
	{
		return PurchaseOrder::where('id', $id)->firstOrFail()->isCommitted;
	}

}
