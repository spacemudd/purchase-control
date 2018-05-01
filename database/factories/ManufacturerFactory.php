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

    return [
        'name' => $faker->company,
        'website' => $faker->domainName,
        'support_url' => $faker->domainName . '/support',
        'support_phone' => $faker->phoneNumber,
        'support_email' => $faker->companyEmail,
        'created_by_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
