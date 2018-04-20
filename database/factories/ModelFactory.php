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

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$faker_sa = \Faker\Factory::create('ar_SA');

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Department::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'code' => $faker->unique()->randomNumber(9),
        'description' => $faker->company(),
        'head_department' => $faker->name(),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Employee::class, function (Faker\Generator $faker) use ($faker_sa) {
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
