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

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class ItemTemplate extends Model implements AuditableContract
{
    use Auditable;

    protected $appends = ['link'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function assets()
    {
        return $this->hasMany(Item::class);
    }

    public function assets_outside()
    {
        // Duplicates the assets' relationship to help around the with(); query builder.
        return $this->hasMany(Item::class);
    }

    public function in_stock_assets()
    {
        return $this->hasMany(Item::class);
    }

    public function configurations()
    {
        return $this->hasMany(AssetConfiguration::class);
    }

    public function getLinkAttribute()
    {
        return route('asset-templates.show', ['id' => $this->id]);
    }

    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
