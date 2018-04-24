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

use Brick\Money\Money;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class ItemTemplate extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'model_number',
        'manufacturer_id',
        'eol',
        'default_unit_price_minor',
    ];

    protected $appends = ['link'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function getUnitPriceAttribute()
    {
        if($this->default_unit_price_minor) {
            return Money::ofMinor($this->default_unit_price_minor, 'SAR')->getAmount();
        }

        return null;
    }

    public function getLinkAttribute()
    {
        return route('asset-templates.show', ['id' => $this->id]);
    }
}
