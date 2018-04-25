<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Employee::class, function (Faker $faker) {
    static $department_id;

    return [
        'code' => $faker->unique()->randomNumber(4),
        'department_id' => $department_id ? $department_id : factory(\App\Models\Department::class)->create()->id,
        'staff_type_id' => 1,
        'name' => $faker->name(),
        'email' => $faker->unique()->email(),
        'phone' => $faker->e164PhoneNumber(),
    ];
});
