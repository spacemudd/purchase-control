<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\PurchaseTerm::class, function (Faker $faker) {

    static $type_id;

    return [
        'type_id' => $type_id ? $type_id : factory(\App\Model\PurchaseTermsType::class)->create()->id,
        'value' => $faker->text(200, true),
    ];
});
