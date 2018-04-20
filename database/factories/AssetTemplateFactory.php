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

$factory->define(App\Models\ItemTemplate::class, function (Faker $faker) {

    static $manufacturer_id;

    return [
        'code' => 'RT-' . $faker->unique()->randomNumber(),
        'manufacturer_id' => $manufacturer_id ?: factory(\App\Models\Manufacturer::class)->create()->id,
        'type_id' => $faker->randomElement([1, 2]),
        'category_id' => $faker->randomElement([9, 7, 6, 8, 4, 3, 2]),
        'description' => $faker->sentence(),
        'unit_price' => $faker->randomFloat(2, 250, 495000),
        'details' => $faker->text(500),
        'active' => $faker->boolean(70),
    ];
});
