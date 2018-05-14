<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Address::class, function (Faker $faker) {
    return [
        'location' => $faker->address,
        'type' => $faker->randomElement([0, 1]),
        'department' => $faker->company,
        'contact_name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
    ];
});
