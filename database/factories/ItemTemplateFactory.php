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
        'name' => $faker->words(3, true),
        'model_number' => $faker->numberBetween(500),
        'category_id' => null,
        'manufacturer_id' => $manufacturer_id ?: factory(\App\Models\Manufacturer::class)->create()->id,
        'eol' => $faker->text(),
    ];
});
