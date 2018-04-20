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

    return [
        'order_number' => 'PO-' . $faker->unique()->randomNumber(),
        'date' => $faker->dateTimeBetween('-10 years', '+10 years'),
        'vendor_id' => factory(\App\Models\Vendor::class)->create()->id,
        'delivery_number' => $faker->randomNumber(6),
        'main_order_number' => $faker->randomNumber(6),
        'department_id' => factory(\App\Models\Department::class)->create()->id,
        'employee_id' => factory(\App\Models\Employee::class)->create()->id,
        'delivery_date' => $faker->date(),
        'status' => 'draft',
        'created_by_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
