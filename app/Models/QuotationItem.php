<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'vendor_id',
        'description',
        'qty',
        'unit_price',
        'vat',
        'total_price_ex_vat',
        'total_price_inc_vat',
    ];
}
