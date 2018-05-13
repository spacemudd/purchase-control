<?php

namespace App\Clarimount\Service;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use App\Models\PurchaseRequisitionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class PurchaseOrderRequisitionItemsService
{
    /**
     *
     * @param $po_id Purchase Order ID.
     */
    public function store($po_id)
    {
        $request = request()->except('_token');

        DB::transaction(function() use ($request, $po_id) {
            $po = PurchaseOrder::findOrFail($po_id);
            $pr_item = PurchaseRequisitionItem::findOrFail($request['pr_item_id']);

            $item_template = optional($pr_item->item_template);

            // We begin by creating a new Purchase Order and copying over the ItemTemplate details.
            $po_item = PurchaseOrdersItem::create([
                'purchase_order_id' => $po->id,
                'item_template_id' => $item_template->id,
                'code' => $pr_item->code,
                'description' => $pr_item->name,
                'manufacturer_id' => $item_template ? optional($item_template->manufacturer)->name : null,
                'unit_price_minor' => $item_template->default_unit_price_minor,
                'qty' => 1,
                'total_minor' => $item_template->default_unit_price_minor,
            ]);

            $po_item->save();
        });
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'pr_item_id' => 'required|exists:purchase_requisition_items,id',
        ]);
    }
}
