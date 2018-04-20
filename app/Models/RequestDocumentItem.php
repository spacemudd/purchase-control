<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;

class RequestDocumentItem extends Model
{
    protected $fillable = ['request_document_id', 'item_id', 'qty', 'unit_price_minor', 'total_minor', 'currency'];

    protected $hidden = ['unit_price_minor', 'total_minor'];

    protected $appends = ['unit_price', 'total'];

    public function getUnitPriceAttribute()
    {
        return Money::ofMinor($this->unit_price_minor, $this->currency)->getAmount();
    }

    public function getTotalAttribute()
    {
        return Money::ofMinor($this->total_minor, $this->currency)->getAmount();
    }

    public function request()
    {
        return $this->belongsTo(RequestDocument::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
