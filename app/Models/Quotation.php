<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'material_request_id',
        'vendor_id',
        'vendor_quotation_number',
    ];

    public function material_request()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
