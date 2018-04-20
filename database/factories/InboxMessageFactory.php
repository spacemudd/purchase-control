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

$factory->define(App\Models\InboxMessage::class, function (Faker $faker) {

    static $recipient_id;

    return [
        'recipient_id' => $recipient_id,
        'type' => $faker->randomElement(['System Message', 'Personal Message']),
        'content' => $faker->sentence(),
        'seen_at' => $faker->randomElement([$faker->dateTime(), NULL]),
    ];
});
