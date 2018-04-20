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

    static $asset_template_id;
    static $manufacturer_serial_number;
    static $system_tag_number;
    static $finance_tag_number;
    static $unit_price;
    static $warranty_expiry_date;

    return [
        'purchase_order_id' => factory(PurchaseOrder::class)->create()->id,
        'asset_template_id' => $asset_template_id ? $asset_template_id : factory(\App\Models\ItemTemplate::class)->create()->id,
        'manufacturer_serial_number' => $manufacturer_serial_number ?: $faker->unique()->isbn10,
        'system_tag_number' => $system_tag_number ?: $faker->unique()->isbn10,
        'finance_tag_number' => $finance_tag_number ?: $faker->unique()->isbn13,
        'rfid' => null,
        'unit_price' => $unit_price ?: $faker->randomFloat(),
        'warranty_expiry_date' => $warranty_expiry_date ?: $faker->dateTimeThisYear(),
        'received_at' => $faker->dateTime(),
    ];
});
