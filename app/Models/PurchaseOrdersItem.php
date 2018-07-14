<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Models;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class PurchaseOrdersItem extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'pr_item_id',
        'code',
        'name',
        'description',
        'manufacturer_id',
        'manufacturer_serial_number',
        'system_tag_number',
        'finance_tag_number',
        'rfid',
        'unit_price_minor',
        'qty',
        'total_minor',
        'warranty_expires_at',
        'received_at',
        'received_by_id',
        'item_template_id',
        'discount_flat_minor',
        'subtotal_minor',
        'tax_rate1',
        'tax_amount_1_minor',
        'taxes',
        'discounts',
    ];

	protected $dates = ['created_at', 'updated_at', 'date', 'received_at', 'warranty_expires_at'];

    // protected $hidden = ['unit_price_minor', 'total_minor'];

    protected $appends = ['unit_price', 'total', 'subtotal', 'discount_flat'];

    protected $casts = [
        'taxes' => 'object',
        'subtotal_minor' => 'integer',
        'discounts' => 'object',
    ];

	public function purchase_order()
	{
		return $this->belongsTo(PurchaseOrder::class);
	}

	public function item_catalog()
	{
	    return $this->belongsTo(ItemTemplate::class, 'item_template_id');
	}

	public function item()
	{
	    return $this->belongsTo(Item::class);
	}

    public function getUnitPriceAttribute()
    {
        return Money::ofMinor($this->unit_price_minor, 'SAR')->getAmount();
    }

    public function getTotalAttribute()
    {
        return Money::ofMinor($this->total_minor, 'SAR')->getAmount();
    }

    public function getSubtotalAttribute()
    {
        if($this->subtotal_minor) {
            return Money::ofMinor($this->subtotal_minor, 'SAR')->getAmount();
        }
    }

    public function getDiscountFlatAttribute()
    {
        if($this->discount_flat_minor) {
            return Money::ofMinor($this->discount_flat_minor, 'SAR')->getAmount();
        }
    }

    /**
     * Deprecated.
     *
     * @return \Brick\Math\BigDecimal
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function getTaxAmount1Attribute()
    {
        if($this->tax_amount_1_minor) {
            return Money::ofMinor($this->tax_amount_1_minor, 'SAR')->getAmount();
        }
    }

    /**
     * Calculates the total amount of taxes applied.
     *
     */
    public function getTotalTaxesAmountAttribute()
    {
        $taxAmount = 0;

        if($this->taxes) {
            foreach($this->taxes as $tax) {
                $taxAmount += $tax->amount;
            }
        }

        return Money::ofMinor($taxAmount, $this->purchase_order->currency)->getAmount();
    }

    /**
     * To remove useless zeroes.
     *
     * @param $qty
     * @return float
     */
    public function getQtyAttribute($qty)
    {
        return floatval($qty);
    }

    public function getSubtotalBeforeDiscountAttribute()
    {
         return Money::ofMinor($this->unit_price_minor, 'SAR')->multipliedBy($this->getOriginal('qty'))->getAmount();
    }
}
