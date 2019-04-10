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

use App\VendorBank;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Vendor extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

	protected $fillable = [
	    'name',
        'established_at',
        'address',
        'telephone_number',
        'fax_number',
        'email',
        'website',
    ];

	protected $appends = ['link'];

	public function getLinkAttribute()
	{
	    return route('vendors.show', ['id' => $this->id]);
	}

	public function getDisplayNameAttribute()
	{
	    return $this->id. ' - ' . $this->name;
	}

	public function getWebsiteLinkAttribute()
	{
	    $url = $this->website;
        if ($ret = parse_url($url)) {
            if(!isset($ret["scheme"]) )
            {
                $url = 'https://' . $url;
            }
        }

        return $url;
	}

	public function reps()
	{
	    return $this->hasMany(VendorRep::class);
	}

	public function bank()
	{
	    return $this->hasOne(VendorBank::class);
	}

	public function manufacturers()
	{
	    return $this->belongsToMany(Manufacturer::class);
	}

	public function quotations()
	{
	    return $this->hasMany(Quotation::class);
	}
}
