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

$factory->define(\App\Models\Manufacturer::class, function (Faker $faker) {
    static $code;

    return [
        'code' => $code ? $code : $faker->unique()->randomNumber(5),
        'description' => $faker->catchPhrase,
        'description_slug' => str_slug($faker->catchPhrase),
        'active' => $faker->boolean(80),
    ];
});
