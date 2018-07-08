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
use App\Models\PurchaseOrdersItem;
use App\Models\PurchaseOrder;
use App\Models\UserActivity;
use Carbon\Carbon;

class PurchaseOrderItemRepository
{
	
	protected $model;

	function __construct(PurchaseOrdersItem $model)
	{
		$this->model = $model;
	}

	public function index($purchase_order_id)
	{
		return PurchaseOrdersItem::where('purchase_order_id', $purchase_order_id)
            ->with('item_catalog')
            ->with('purchase_order')
            ->with('item')
            ->get();
	}

	public function getPurchaseOrderBy($column, $id)
	{
		return PurchaseOrder::where($column, $id)->firstOrFail();
	}

	public function destroy($purchase_order_id, $id)
	{
        return $this->model->where('purchase_order_id', $purchase_order_id)
							->where('id', $id)
							->delete();
	}

	public function create($model)
	{
		return $this->model->create($model);
	}

	public function find($id)
	{
		return $this->model->findOrFail($id);
	}

	public function findById($id)
	{
        return PurchaseOrdersItem::where('purchase_order_id', $id)
            ->with('purchase_order')
            ->with('item')
            ->firstOrFail();
	}

	public function update($id, $model)
	{
        $asset = PurchaseOrdersItem::where('id', $id)->first()->toArray();

		return $this->model->where('id', $id)->update($model);
	}

	public function partialUpdate($id, $model)
	{
        return PurchaseOrdersItem::where('id', $id)
            ->first()
            ->update([
                'manufacturer_serial_number' => $model['manufacturer_serial_number'],
                'system_tag_number' => $model['system_tag_number'],
                'finance_tag_number' => $model['finance_tag_number'],
                'unit_price' => $model['unit_price'],
            ]);
	}

    /**
     * Marks a PO's item as receive and duplicates it to the assets table.
     *
     * @param $item
     * @param $poItem
     * @return
     */
	public function receive(array $item, array $poItem)
	{
		Item::create($item);

        $activity = new UserActivity();
        $activity->user_id = auth()->user()->id;
        $activity->target_id = $poItem['id'];
        $activity->target_entity = 'PURCHASE_ORDER_ASSET';
        $activity->action_type = 'ASSET_RECEIVE';
        $activity->after_changes_json = json_encode($item);
        $activity->save();

		return PurchaseOrdersItem::where('id', $poItem['id'])
									->update([
												'received_at' => Carbon::now(),
											]);
	}

}
