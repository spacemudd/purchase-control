<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionItem extends Model
{
    protected $fillable = [
        'purchase_requisition_id',
        'item_template_id',
        'code',
        'name',
        'model_number',
        'manufacturer',
        'description',
        'qty',
    ];

    public function purchase_requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function item_template()
    {
        return $this->belongsTo(ItemTemplate::class);
    }
}
