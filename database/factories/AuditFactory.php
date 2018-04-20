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
use Illuminate\Support\Collection;

$factory->define(App\Models\Audit::class, function (Faker $faker) {
    static $user_id;

    return [
        'user_id' => $user_id ? $user_id : 1,
        'event' => $faker->randomElement(['inserted', 'updated', 'deleted']),
        'auditable_id' => $faker->randomDigit,
        'auditable_type' => 'App\Models\\' . $faker->word,
        'old_values' => Collection::make(['id' => 'some-number', 'something' => $faker->words(3, 1)])->toJson(),
        'new_values' => Collection::make(['id' => $faker->randomNumber(5), 'something' => $faker->words(4, 1)])->toJson(),
        'url' => $faker->url,
        'ip_address' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'tags' => null,
    ];
});
