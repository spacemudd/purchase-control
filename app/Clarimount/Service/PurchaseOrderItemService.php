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

use App\Clarimount\Repository\PurchaseOrderItemRepository;

use App\Models\AssetStatus;
use App\Models\Item;
use App\Models\ItemTemplate;
use App\Models\PurchaseOrdersLine;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;

class PurchaseOrderItemService
{
	protected $repository;

	public function __construct(PurchaseOrderItemRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index($purchase_order_id)
	{
		return $this->repository->index($purchase_order_id);
	}

	public function showServicesItems()
	{
	    $request = request()->only('purchase_order_id');
	    $this->validateShowServicesItemsRequest($request)->validate();

	    return PurchaseOrdersLine::where('purchase_order_id', $request['purchase_order_id'])
            ->with('received_by')
            ->get();
	}

	public function validateShowServicesItemsRequest(array $data)
	{
	    return Validator::make($data, [
	        'purchase_order_id' => 'required|exists:purchase_orders,id',
        ]);
	}

    /**
     * Retrieves a purchase order by column name.
     *
     * @param string $column
     * @param $value
     * @return mixed
     */
    public function getPurchaseOrderBy($column = 'id', $value)
	{
		return $this->repository->getPurchaseOrderBy($column, $value);
	}

	public function destroy($purchase_order_id, $id)
	{
		return $this->repository->destroy($purchase_order_id, $id);
	}

	public function destroyServiceLine()
	{
	    $request = request()->only('id');

	    $line = PurchaseOrdersLine::where('id', $request['id'])->firstOrFail();

	    // Abort if PO is draft
        $this->abortIfPoNotDraft($line->purchase_order_id);

	    return PurchaseOrdersLine::where('id', $request['id'])->delete();
	}

    /**
     * This is deprecated in favor of itemsUpdate @ Api/PurchaseOrderItemController.php
     *
     * @param $purchase_order_id
     * @return mixed
     */
	public function store($purchase_order_id)
	{
		$data = request()->except(['_token']);
		$data['purchase_order_id'] = $purchase_order_id;

		$this->validate($data)->validate();

		$poCurrency = trim(PurchaseOrder::where('id', $purchase_order_id)->firstOrFail()->currency);

        $unitPrice = Money::of($data['unit_price'], $poCurrency ?: 'SAR');
        $total = $unitPrice->multipliedBy($data['qty'], RoundingMode::HALF_UP);
        $data['unit_price_minor'] = $unitPrice->getMinorAmount()->toInt();
        $data['total_minor'] = $total->getMinorAmount()->toInt();

        //// Create an item template if it doesnt exist.
        //if( ! $data['item_id'] ) {
        //    $newItem = $data;
        //    $newItem['code'] = $data['name'];
        //    $newItem['description'] = $data['name'];
        //    $newItem['default_unit_price'] = $data['unit_price_minor'];
        //
        //    $data['item_id'] = Item::create($newItem)->id;
        //}

        return $this->repository->create($data);
	}

    /**
     * Returns the warranty expiry date based on the purchase order's date,
     * quantity selected and duration.
     *
     * @param \App\Models\PurchaseOrder $purchaseOrder
     * @param int $quantity
     * @param string $duration
     * @return \Carbon\Carbon
     */
    public function calculateWarrantyExpiryDate(PurchaseOrder $purchaseOrder, int $quantity, string $duration): Carbon
    {
        $po_date = $purchaseOrder->date->copy();

        switch ($duration) {
            case 'year':
                $expiry_date = $po_date->addYears($quantity);
                break;
            case 'month':
                $expiry_date = $po_date->addMonths($quantity);
                break;
            case 'week':
                $expiry_date = $po_date->addWeeks($quantity);
                break;
            case 'day':
                $expiry_date = $po_date->add($quantity);
                break;
            default:
                throw \Exception('Duration of the the warranty expiry is wrong');
                break;
        }

        return $expiry_date;
    }

	public function attemptToReceiveItem($purchase_order_id, $id)
	{
		$this->failIfNotCommitted($purchase_order_id);

		return $this->isItemReadyForAssetsTable($id);
	}

	/**
	 * Marks a PO's item as received and duplicates it to the assets table.
	 * 
	 * @param  int $purchase_order_id
	 * @param  int $id
	 * @return array
	 */
	public function receive($purchase_order_id, $id)
	{
		// Swapped to using request()
		$item = request()->only(['purchase_order_id', 'id']);

		$purchaseOrder = PurchaseOrder::where('id', request()->purchase_order_id)->firstOrFail()->toArray();
		$poItem = PurchaseOrdersItem::where('id', request()->id)
                    ->with(['asset_template' => function($q) {
                    $q->with('manufacturer');
                }])
                ->firstOrFail()
                ->toArray();

		$item['manufacturer_serial_number'] = $poItem['manufacturer_serial_number'];
		$item['finance_serial_number'] = $poItem['finance_tag_number'];
		$item['system_tag_number'] = $poItem['system_tag_number'];
		$item['location_id'] = $poItem['location_id'];

		$this->validateReceiveItem($item)->validate();

		$preparedAsset = $this->prepareItemForAssetTable($purchaseOrder, $poItem);

		return $this->repository->receive($preparedAsset, $poItem);
	}

	public function prepareItemForAssetTable(array $purchaseOrder, array $poItem): array
	{
	    $assetRecord = [
	        'asset_template_id' => $poItem['asset_template']['id'],
            'purchase_order_id' =>  $purchaseOrder['id'],
            'unit_price' => $poItem['unit_price'],
            'manufacturer_serial_number' => $poItem['manufacturer_serial_number'],
            'system_tag_number' => $poItem['system_tag_number'],
            'finance_tag_number' => $poItem['finance_tag_number'],
            'warranty_expiry' => $poItem['warranty_expiry_date'],
            'asset_status_id' => AssetStatus::where('name', 'In Stocks')->firstOrFail()->id,
            'manufacturer_name' => $poItem['asset_template']['manufacturer']['code'],
            'location_id' => $poItem['location_id'],
        ];

	    return $assetRecord;
	}

	public function receiveServiceItem()
	{
	    $request = request()->only('id');

	    $item = PurchaseOrdersLine::where('id', $request['id'])->firstOrFail();

	    $this->failIfNotCommitted($item->purchase_order_id);

	    if($item->received_at) {
	        return response()->json([
	            'message' => 'Item is already received',
            ], 422);
        }

	    return PurchaseOrdersLine::where('id', $request['id'])->update([
            'received_by_id' => auth()->user()->id,
            'received_at' => Carbon::now(),
        ]);
	}

    /**
     * Fails if the Purchase Order is not committed.
     *
     * @param  int $purchase_order_id
     * @return \App\Clarimount\Service\abort|void
     * @throws \Exception
     */
	public function failIfNotCommitted($purchase_order_id)
	{
		$po = PurchaseOrder::where('id', $purchase_order_id)->firstOrFail();

		if(!$po->isCommitted) {
		    throw new \Exception('PO is not committed');
			return abort(403);
		}
	}

    /**
     * Checking an item if ready for duplicating to assets table.
     *
     * @param $id
     * @return bool
     * @internal param array $ids
     */
	public function isItemReadyForAssetsTable($id)
	{
		$item = PurchaseOrdersItem::where('id', $id)->firstOrFail();

		$required_elements = ['manufacturer_serial_number'];

		foreach($required_elements as $required_element) {
			if( ! $item[$required_element]) {
				return false;
			}
		}

		return true;
	}

	public function edit($id)
	{
		return $this->repository->findById($id);
	}

	public function update($purchase_order_id, $id)
	{
		$item = request()->except(['_token', '_method']);

		$item['purchase_order_id'] = $purchase_order_id;

		$this->validateUpdate($item)->validate();

		return $this->repository->update($id, $item);
	}

	public function partialUpdate()
	{
	    $item = request()->except(['_token', '_method']);
	    $this->partialUpdateValidator($item)->validate();
	    return $this->repository->partialUpdate($item['purchase_order_item_id'], $item);
	}

	public function partialUpdateValidator(array $data)
	{
	    return Validator::make($data, [
	        'purchase_order_item_id' => 'required|exists:purchase_orders_items,id',
            'manufacturer_serial_number' => [
                'required',
                Rule::unique('purchase_orders_items')->ignore($data['manufacturer_serial_number'], 'manufacturer_serial_number'),
                Rule::unique('assets')
            ],
            'system_tag_number' => 			[
                'nullable',
                Rule::unique('purchase_orders_items')->ignore($data['system_tag_number'], 'system_tag_number'),
                Rule::unique('assets')
            ],
            'finance_tag_number' => 		[
                'nullable',
                Rule::unique('purchase_orders_items')->ignore($data['finance_tag_number'], 'finance_tag_number'),
                Rule::unique('assets')
            ],
            'unit_price' => 'nullable|numeric|max:10000000',
        ]);
	}

	public function show($id)
	{
		return $this->repository->find($id);
	}

	public function validateReceiveItem(array $data)
	{
		return Validator::make($data, [
            'id' => 'required|exists:purchase_orders_items,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'manufacturer_serial_number' => 'required|string|max:255|unique:assets',
            'finance_tag_number' => 'nullable|string|max:255|unique:assets',
            'system_tag_number' => 'nullable|string|max:255|unique:assets',
            'location_id' => 'nullable|exists:locations,id',
        ]);
	}

	public function validate(array $data)
	{
		return Validator::make($data, [
		    'purchase_order_id' => 'required|exists:purchase_orders,id',
            'item_id' => 'nullable|exists:items,id',
            'name' => 'required|string|max:255,',
            'qty' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:1',
        ]);
	}

    public function validateUpdate(array $data)
    {
        $validator = Validator::make($data, [
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'manufacturer_serial_number' => [
                'required',
                Rule::unique('assets')
            ],
            'system_tag_number' => 			[
                'nullable',
                Rule::unique('assets')
            ],
            'finance_tag_number' => 		[
                'nullable',
                Rule::unique('assets')
            ],
            'unit_price' => 'required|numeric',
        ]);

        return $validator;
    }

    public function storeServiceLine()
    {
        $request = request()->except('_token');

        $this->validateServiceLine($request)->validate();

        // Abort if the PO is any state other than 'draft',
        $this->abortIfPoNotDraft($request['purchase_order_id']);

        $serviceLine = new PurchaseOrdersLine();
        $serviceLine->purchase_order_id = $request['purchase_order_id'];
        $serviceLine->type = 'service';
        $serviceLine->description = $request['description'];
        $serviceLine->quantity = $request['quantity'];
        $serviceLine->unit_cost = $request['unit_cost'];
        $serviceLine->save();

        return $serviceLine;
    }

    public function validateServiceLine(array $data)
    {
        return Validator::make($data, [
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'description' => 'required|min:5|max:199',
            'quantity' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
        ]);
    }

    public function abortIfPoNotDraft($purchase_order_id)
    {
        $status = PurchaseOrder::where('id', $purchase_order_id)->firstOrFail()->status;
        if($status != 'draft') {
            throw new \Exception('Purchase order is not in draft mode.');
        }
    }

}
