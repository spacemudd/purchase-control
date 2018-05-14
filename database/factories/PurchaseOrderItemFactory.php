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

use Faker\Generator as Faker;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;

$factory->define(PurchaseOrdersItem::class, function (Faker $faker) {

    static $item_template_id;
    static $manufacturer_serial_number;
    static $system_tag_number;
    static $finance_tag_number;
    static $unit_price;
    static $warranty_expiry_date;

    if(!$item_template_id) {
        $item_template = factory(\App\Models\ItemTemplate::class)->create();
    } else {
        $item_template = \App\Models\ItemTemplate::find($item_template_id);
    }

    $unit_price_minor = $faker->numberBetween(0, 5000);
    $qty = $faker->numberBetween(0, 10);
    $total = $unit_price_minor * $qty;

    return [
        'purchase_order_id' => factory(PurchaseOrder::class)->create()->id,
        'item_template_id' => $item_template->id,
        'code' => $item_template->code,
        'description' => $item_template->name,
        'unit_price_minor' => $unit_price_minor,
        'qty' => $qty,
        'total_minor' => $total,
    ];
});
