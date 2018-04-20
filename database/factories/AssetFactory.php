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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Item::class, function (Faker\Generator $faker) {

    static $asset_template_id;
    static $purchase_order_id;
    static $manufacturer_name;
    static $asset_status_id;

    return [
        'purchase_order_id' => $purchase_order_id ?: $purchase_order_id = null,
        'asset_template_id' => $asset_template_id ?: factory(\App\Models\ItemTemplate::class)->create()->id,
        'asset_status_id' => $asset_status_id ?: \App\Models\AssetStatus::where('code', 'active')->first()->id,
        'unit_price' => $faker->randomFloat(2, 250, 495000),
        'condition' => $faker->randomElement(['good', 'damaged', 'defective']),
        'manufacturer_serial_number' => $faker->unique()->ean13,
        'system_tag_number' => $faker->unique()->ean8,
        'finance_tag_number' => $faker->unique()->isbn10,
        'warranty_expiry' => $faker->dateTimeBetween('-10 years', '+10 years'),
        'active' => $faker->boolean(70),
        'manufacturer_name' => $manufacturer_name ?: null,
    ];
});
