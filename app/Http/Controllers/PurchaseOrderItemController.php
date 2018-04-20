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

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Clarimount\Service\ManufacturerService;
use App\Clarimount\Service\PurchaseOrderItemService;

use App\Models\ItemCategory;

class PurchaseOrderItemController extends Controller
{
	
	protected $service;
	protected $manufacturer_service;

	public function __construct(PurchaseOrderItemService $service,
								ManufacturerService $manufacturer_service)
	{
		$this->service = $service;
		$this->manufacturer_service = $manufacturer_service;
	}

	public function create($purchase_order_id)
	{
		$purchase_order = $this->service->getPurchaseOrderBy('id', $purchase_order_id);
		$manufacturers = $this->manufacturer_service->index();
        $categories = ItemCategory::all();

		return view('purchase-orders-items.create', compact('purchase_order', 'manufacturers', 'categories'));
	}

	public function store($purchase_order_id)
	{
		$this->service->store($purchase_order_id);

		session()->flash('status', 'success');
        session()->flash('message', trans('statements.successfully-saved'));

        return redirect()->route('purchase-orders.show', ['id' => $purchase_order_id]);
	}

	public function edit($purchase_order_id, $id)
	{
		$item = $this->service->edit($id);

		return view('purchase-orders-items.edit', compact('item'));
	}

	public function update($purchase_order_id, $id)
	{
		$this->service->update($purchase_order_id, $id);

		session()->flash('status', 'success');
        session()->flash('message', trans('statements.successfully-saved'));

		return redirect()->route('purchase-orders.show', ['id' => $purchase_order_id]);
	}
}
