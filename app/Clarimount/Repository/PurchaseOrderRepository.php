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

use App\Models\Item;
use App\Models\MaxNumber;
use App\Models\Media;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseOrderRepository
{
	
	protected $model;
	protected $purchase_orders_item;

	function __construct(PurchaseOrder $model,
						PurchaseOrdersItem $purchase_orders_item)
	{
		$this->model = $model;
		$this->purchase_orders_item = $purchase_orders_item;
	}

	public function all()
	{
		return $this->model->all();
	}

	public function paginatedIndex($per_page)
	{
		return $this->model->with(['employee', 'vendor', 'department'])->latest()->paginate($per_page);
	}

	public function destroy($id)
	{
        return $this->model->where('id', $id)->delete();
	}

	public function create($model)
	{
        return $this->model->create($model);
	}

	public function find($id)
	{
		return $this->model->where('id', $id)->with(['items', 'vendor', 'files', 'department', 'employee'])->firstOrFail();
	}

    /**
     *
     * @param $id int
     * @param $purchaseOrder array
     * @return \App\Models\PurchaseOrder
     */
    public function update($id, array $purchaseOrder)
	{
		$this->model->find($id, ['id'])->update($purchaseOrder);

		return $this->model->find($id);
	}

    public function save($id)
    {
        $po = DB::transaction(function() use ($id) {

            // The locking.
            $po = $this->model->where('id', $id)->lockForUpdate()->first();

            // Some validation.
            if($po->status != PurchaseOrder::NEW) throw new \Exception('PO must be in draft mode to be approved.');

            // Calculating the new request number.
            $numberPrefix = 'PO-' . Carbon::now()->format('Y-m');
            $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                'name' => $numberPrefix,
            ], [
                'value' => 0,
            ]);

            $number = ++$maxNumber->value;

            // The updates.
            $po->number = $maxNumber->name . '-' . sprintf('%05d', $number);
            $po->status = PurchaseOrder:: SAVED;
            $po->save();

            // Save the new number.
            $maxNumber->value = $number;
            $maxNumber->save();

            return $po;
        });

        return $po;
    }

	public function commit($id)
	{
        $po = DB::transaction(function() use ($id) {

            // The locking.
            $po = $this->model->where('id', $id)->lockForUpdate()->first();

            // Some validation.
            if($po->status != PurchaseOrder::NEW) throw new \Exception('PO must be in draft mode to be approved.');

            // Calculating the new request number.
            $numberPrefix = 'PO-' . Carbon::now()->format('Y-m');
            $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                'name' => $numberPrefix,
            ], [
                'value' => 0,
            ]);

            $number = ++$maxNumber->value;

            // The updates.
            $po->number = $maxNumber->name . '-' . sprintf('%05d', $number);
            $po->approved_by_id = auth()->user()->id;
            $po->status = PurchaseOrder::APPROVED;
            $po->save();

            // Save the new number.
            $maxNumber->value = $number;
            $maxNumber->save();

            return $po;
        });

	    return $po;
	}

	public function void($id)
	{
        $po = $this->model->where('id', $id)->firstOrFail();

        $this->deleteAllAssets($po);

        $po->status = 'void';
        $po->save();

		return $po;
	}

	public function deleteAllAssets(PurchaseOrder $po)
	{
	    $purchaseOrderItems = PurchaseOrdersItem::where('purchase_order_id', $po->id)->get();

	    // TODO: Also figure out a way to remove the MSN from PurchaseOrdersItems because when adding a new item
        // with the same MSN to another draft PO, an error will result.

        $transaction = \DB::transaction(function() use ($purchaseOrderItems) {
            foreach($purchaseOrderItems as $item) {
                Item::where('manufacturer_serial_number', $item['manufacturer_serial_number'])->delete();
            }
        }, 5);

        return $transaction;
	}

    public function attachments($id)
    {
        return $this->find($id)->files()->get();
    }

    /**
     *
     * @param $id int App\Models\Media ID
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Exception
     */
    public function downloadAttachment($id)
    {
        $attachment = Media::where('id', $id)->firstOrFail();

        if(!file_exists($attachment->file_path)) {
            throw new \Exception('File not found');
        }

        return response()->download($attachment->file_path . $attachment->file_name, $attachment->file_name, [], 'inline');
    }
}
