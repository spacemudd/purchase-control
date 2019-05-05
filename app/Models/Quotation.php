<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    /**
     * When the quotation is still being updated.
     *
     * @var int
     */
    const DRAFT = 0;

    /**
     * When the quotation is saved and not editable anymore.
     *
     * @var int
     */
    const SAVED = 1;

    protected $fillable = [
        'material_request_id',
        'vendor_id',
        'vendor_quotation_number',
        'status',
        'cost_center_id',
    ];

    public function material_request()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function cost_center()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        switch($this->status) {
            case self::DRAFT:
                return 'Draft';
            case self::SAVED:
                return 'Saved';
        }
    }

    /**
     *
     * @return \Brick\Math\BigDecimal
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function totalCost()
    {
        $totalPrice = $this->items()->sum('total_price_inc_vat');

        return Money::ofMinor($totalPrice, 'SAR')->getAmount();
    }
}
