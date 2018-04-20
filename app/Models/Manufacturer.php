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

class Manufacturer extends Model implements AuditableContract
{
    use Auditable;

    public $asYouType = true;

    protected $fillable = ['code', 'description', 'description_slug', 'active'];

    protected $appends = ['link'];

    public function assets()
    {
    	return $this->hasMany(Item::class);
    }

    public function asset_templates()
    {
        return $this->hasMany(ItemTemplate::class);
    }

    public function getDisplayNameAttribute()
    {
    	return $this->code . ' - ' . $this->description;
    }

    public function getLinkAttribute()
    {
        return route('manufacturers.show', ['id' => $this->id]);
    }

    public function scopeActive($q)
    {
    	$q->where('active', true);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
        ];
    }
}
