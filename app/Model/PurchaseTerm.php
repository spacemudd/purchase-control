<?php

namespace App\Model;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;

class PurchaseTerm extends Model
{
    protected $fillable = ['name', 'value'];

    public function purchase_order()
    {
        return $this->belongsToMany(PurchaseOrder::class);
    }
}
