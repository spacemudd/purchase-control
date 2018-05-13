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

use App\Models\Address;
use App\Models\Vendor;
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

        $purchase_order['status'] = PurchaseOrder::NEW;

        if(isset($purchase_order['billing_address_id'])) {
            $purchase_order['billing_address_json'] = Address::where('id', $purchase_order['billing_address_id'])->firstOrFail();
        }

        if(isset($purchase_order['shipping_address_id'])) {
            $purchase_order['shipping_address_json'] = Address::where('id', $purchase_order['shipping_address_id'])->firstOrFail();
        }

        if(isset($purchase_order['vendor_id'])) {
            $purchase_order['vendor_json'] = Vendor::where('id', $purchase_order['vendor_id'])->firstOrFail();;
        }

        $purchase_order['created_by_id'] = auth()->user()->id;



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

	public function save($id=null)
    {
        // did the $id=null cause the API controller isn't injecting the save() w/ the id.
        // the http controller is... injecting.
        if($id) {
            $purchase_order = ['id' => $id];
        } else {
            $purchase_order = request()->only('id');
        }

        $this->validatePoExists($purchase_order)->validate();

        if( ! $this->isPoDraft($purchase_order['id'])) {
            throw new \Exception('The PO must be in draft mode to be saved.');
        }

        return $this->repository->save($purchase_order['id']);
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
            'vendor_id' => 'required|exists:vendors,id',
            'shipping_address_id' => 'nullable|exists:addresses,id',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'currency' => 'nullable|string|max:255',
        ]);

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

	public function updateHistoricalData($id)
	{
        $po = $this->repository->find($id);

        if(isset($po['billing_address_id'])) {
            $po->billing_address_json = Address::where('id', $po->billing_address_id)->firstOrFail();
        } else {
            $po->billing_address_json = null;
        }

        if(isset($po['shipping_address_id'])) {
            $po->shipping_address_json = Address::where('id', $po->shipping_address_id)->firstOrFail();
        } else {
            $po->shipping_address_json = null;
        }

        if(isset($po['vendor_id'])) {
            $po->vendor_json = Vendor::where('id', $po->vendor_id)->firstOrFail();;
        } else {
            $po->vendor_json = null;
        }

        $po->save();
	}

}
