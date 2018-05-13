<?php

namespace App\Clarimount\Service;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use App\Models\PurchaseRequisitionItem;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class PurchaseOrderRequisitionItemsService
{
    /**
     *
     * @param $po_id Purchase Order ID.
     * @return mixed
     */
    public function store($po_id)
    {
        $request = request()->except('_token');

        $po_item = DB::transaction(function() use ($request, $po_id) {
            $po = PurchaseOrder::findOrFail($po_id);
            $pr_item = PurchaseRequisitionItem::findOrFail($request['pr_item_id']);

            $item_template = optional($pr_item->item_template);

            // We begin by creating a new Purchase Order and copying over the ItemTemplate details.
            $unit_price =  $request['unit_price']
                            ? Money::of($request['unit_price'], 'SAR')
                            : Money::ofMinor($item_template->default_unit_price_minor, 'SAR');
            $subtotal = $unit_price->multipliedBy($request['qty'], RoundingMode::HALF_UP);

            // Apply the discount if available.
            if($request['discount']) {
                $subtotal = $subtotal->minus($request['discount']);
            }

            // Get the total tax amount.
            if($request['tax_rate1']) {
                $tax_amount_1 = $subtotal->multipliedBy(($request['tax_rate1']/100), RoundingMode::HALF_UP);
            } else {
                $tax_amount_1 = Money::of(0, 'SAR');
            }

            $total_price = $subtotal->plus($tax_amount_1);

            $po_item = PurchaseOrdersItem::create([
                'purchase_order_id' => $po->id,
                'item_template_id' => $item_template->id,
                'pr_item_id' => $pr_item->id,
                'code' => $pr_item->code,
                'description' => $pr_item->name,
                'manufacturer_id' => $item_template ? optional($item_template->manufacturer)->name : null,
                'unit_price_minor' => $unit_price->getMinorAmount()->toInt(),
                'qty' => $request['qty'] ? $request['qty'] : 1,
                'discount_flat_minor' => $request['discount'] * 100,
                'subtotal_minor' => $subtotal->getMinorAmount()->toInt(),
                'tax_rate1' => $request['tax_rate1'],
                'tax_amount_1_minor' => $tax_amount_1->getMinorAmount()->toInt(),
                'total_minor' => $total_price->getMinorAmount()->toInt(),
            ]);

            $po_item->save();

            return $po_item;
        });

        return $po_item;
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'pr_item_id' => 'required|exists:purchase_requisition_items,id',
        ]);
    }
}
