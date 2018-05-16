<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionSimpleItem extends Model
{
    protected $fillable = [
        'purchase_requisition_id', 'description', 'qty',
    ];

    public function purchase_requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }
}
