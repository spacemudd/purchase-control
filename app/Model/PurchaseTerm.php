<?php

namespace App\Model;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;

class PurchaseTerm extends Model
{
    protected $fillable = ['type_id', 'name', 'value', 'enabled'];

    public function purchase_order()
    {
        return $this->belongsToMany(PurchaseOrder::class);
    }

    public function type()
    {
        return $this->belongsTo(PurchaseTermsType::class, 'type_id');
    }
}
