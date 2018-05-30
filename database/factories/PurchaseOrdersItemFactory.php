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

    static $purchase_order_id;
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
    $qty = $faker->numberBetween(1, 10);
    $subtotal = $unit_price_minor * $qty;
    $tax_rate1 = $faker->randomFloat(2, 0.5, 0.25);
    $tax_amount_1 = \Brick\Money\Money::of($subtotal, 'SAR')
        ->multipliedBy($tax_rate1, \Brick\Math\RoundingMode::HALF_UP)
        ->getMinorAmount()
        ->toInt();
    $total = $subtotal + $tax_amount_1;

    return [
        'purchase_order_id' => $purchase_order_id ? $purchase_order_id : factory(PurchaseOrder::class)->create()->id,
        'item_template_id' => $item_template->id,
        'code' => $item_template->code,
        'description' => $item_template->name,
        'unit_price_minor' => $unit_price_minor,
        'qty' => $qty,
        'subtotal_minor' => $subtotal,
        'tax_rate1' => $tax_rate1,
        'tax_amount_1_minor' => $tax_amount_1,
        'total_minor' => $total,
    ];
});
