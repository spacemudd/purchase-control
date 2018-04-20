<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'established_at' => $faker->date(),
        'address' => $faker->streetAddress,
        'telephone_number' => $faker->phoneNumber,
        'fax_number' => $faker->phoneNumber,
        'email' => $faker->companyEmail,
        'website' => $faker->domainName,
    ];
});
