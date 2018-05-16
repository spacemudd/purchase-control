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

$factory->define(App\Models\PurchaseOrder::class, function (Faker $faker) {
    static $purchase_order_main_id;
    static $status;
    static $number;

    return [
        'department_id' => factory(\App\Models\Department::class)->create()->id,
        'employee_id' => factory(\App\Models\Employee::class)->create()->id,
        'vendor_id' => factory(\App\Models\Vendor::class)->create()->id,
        'purchase_order_main_id' => $purchase_order_main_id,
        'number' => $number ? $number : $faker->unique()->randomNumber(),
        'date' => $faker->dateTimeBetween('-10 years', '+10 years'),
        'delivery_number' => $faker->randomNumber(6),
        'delivery_date' => $faker->date(),
        'delivered_at' => $faker->date(),
        'status' => $status ? $status : \App\Models\PurchaseOrder::NEW,
        //'type' => '',
    ];
});
