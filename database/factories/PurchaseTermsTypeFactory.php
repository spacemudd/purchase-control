<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\PurchaseTermsType::class, function (Faker $faker) {

    return [
        'order' => $faker->randomDigit,
        'name' => $faker->text(200, true),
    ];
});
