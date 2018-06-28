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

use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Manufacturer extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    public $asYouType = true;

    protected $fillable = ['name', 'website', 'support_url', 'support_phone', 'support_email'];

    protected $appends = ['link'];

    public function assets()
    {
    	return $this->hasMany(Item::class);
    }

    public function asset_templates()
    {
        return $this->hasMany(ItemTemplate::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute()
    {
    	return $this->name;
    }

    public function getLinkAttribute()
    {
        return route('manufacturers.show', ['id' => $this->id]);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }
}
