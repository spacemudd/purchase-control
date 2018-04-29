<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\PurchaseRequisition::class, function (Faker $faker) {
    static $number;
    static $status;

    $requestedBy = factory(\App\Models\Employee::class)->create();
    $requestedFor = factory(\App\Models\Employee::class)->create();

    return [
        'requested_by_id' => $requestedBy->id,
        'cost_center_by_id' => $requestedBy->department->id,
        'requested_for_id' => $requestedFor->id,
        'cost_center_for_id' => $requestedFor->department->id,
        'status' => $status ? $status : \App\Models\PurchaseRequisition::UNSET,
        'created_by_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
