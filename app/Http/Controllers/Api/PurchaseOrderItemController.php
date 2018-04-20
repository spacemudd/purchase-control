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

namespace App\Http\Controllers\Api;

use App\Clarimount\Service\PurchaseOrderItemService;
use App\Http\Controllers\Controller;

class PurchaseOrderItemController extends Controller
{

	protected $service;

	public function __construct(PurchaseOrderItemService $service)
	{
		$this->service = $service;
	}

    /**
     * Gets the list of the items of a purchase order.
     *
     * @param $purchase_order_id
     * @return mixed
     */
	public function index($purchase_order_id)
	{
		return $this->service->index($purchase_order_id);
	}

	public function showServicesItems()
	{
	    return $this->service->showServicesItems();
	}

	public function delete($purchase_order_id, $id)
	{
		return $this->service->destroy($purchase_order_id, $id);
	}

	public function deleteServiceLine()
	{
	    return $this->service->destroyServiceLine();
	}

	public function store($purchase_order_id)
	{
        return $this->service->store($purchase_order_id);
	}

	public function storeServiceLine()
	{
        return $this->service->storeServiceLine();
	}

	public function partialUpdate()
	{
        $updated = $this->service->partialUpdate();

        if($updated) {
            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
	}

	/**
	 * Attempt to receive a PO's item as received and duplicates it to the assets table.
	 * 
	 * @param  int $purchase_order_id
	 * @param  int $id
	 * @return array ['status' => '', 'message' => '']
	 */
	public function attemptToReceiveItem($purchase_order_id, $id)
	{
        $ableToReceive = $this->service->attemptToReceiveItem($purchase_order_id, $id);

        if($ableToReceive) {
			return $this->service->receive($purchase_order_id, $id);
		} else {
			$response = ['status' => 422, 'message' => trans('statements.po-items-not-ready-for-assets-table')];
			return response()->json($response, 422);
		}
	}

	public function receiveServiceItem()
	{
        return $this->service->receiveServiceItem();
	}
}
