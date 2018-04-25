<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Department::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->randomNumber(9),
        'description' => $faker->company(),
        'head_department' => $faker->name(),
    ];
});
