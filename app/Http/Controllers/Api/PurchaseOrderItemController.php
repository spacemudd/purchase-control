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
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Deletes all the PO's items and inserts the new set.
     *
     * @param \Illuminate\Http\Request $request
     * @param $poId
     * @return \Illuminate\Http\JsonResponse
     */
	public function itemsUpdate(Request $request, $poId)
	{
	    $po = PurchaseOrder::draft()->where('id', $poId)->firstOrFail();

	    DB::transaction(function() use ($po, $request) {

            PurchaseOrdersItem::where('purchase_order_id', $po->id)->delete();

            $currency = trim($po->currency) ?: 'SAR';

            foreach($request->items as $item) {
                // todo: make this in the event listener.

                $total = Money::of(0, $po->currency);

                $unitPrice = Money::of($item['unit_price'], $currency);
                $subtotal = $unitPrice->multipliedBy($item['qty'], RoundingMode::HALF_UP);
                $data = [
                    'purchase_order_id' => $po->id,
                    'item_template_id' => $item['item_catalog']['id'],
                    'qty' => $item['qty'],
                    'unit_price_minor' => $unitPrice->getMinorAmount()->toInt(),
                    'subtotal_minor' => $subtotal->getMinorAmount()->toInt(),
                ];

                $total = $total->plus($subtotal, RoundingMode::HALF_UP);

                if($item['taxes']) {
                    foreach ($item['taxes'] as $tax) {
                        $taxAmount = $subtotal->multipliedBy($tax['current_tax_rate'] / 100, RoundingMode::HALF_UP);
                        $tax['amount'] = $taxAmount->getMinorAmount()->toInt();

                        $total = $total->plus($taxAmount, RoundingMode::HALF_UP);

                        // Add the tax object to the item with teh amount...
                        $data['taxes'][] = $tax;
                    }
                }

                $data['total_minor'] = $total->getMinorAmount()->toInt();

                return PurchaseOrdersItem::create($data);
            }

        }, 2);

	    return $po;
	}
}
