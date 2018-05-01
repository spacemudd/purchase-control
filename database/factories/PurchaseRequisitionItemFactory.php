<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\PurchaseRequisitionItem::class, function (Faker $faker) {
    static $item_template_id;

    if($item_template_id) {
        $item_template = \App\Models\ItemTemplate::find($item_template_id);
    } else {
        $item_template = factory(\App\Models\ItemTemplate::class)->create();
    }

    return [
        'purchase_requisition_id' => factory(\App\Models\PurchaseRequisition::class)->create()->id,
        'item_template_id' => $item_template_id ? $item_template_id : $item_template->id,
        'code' => $item_template->code,
        'name' => $item_template->name,
        'model_number' => $item_template->model_number,
        'manufacturer' => $item_template->manufacturer->name,
        'description' => null,
        'qty' => $faker->randomNumber(1),
    ];
});
