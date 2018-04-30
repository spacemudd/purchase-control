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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];

    protected $hidden = [
        'model_type', 'model_id', 'disk', 'file_path', 'user_id',
    ];

    protected $appends = ['created_rss', 'created_w3c', 'created_at_human'];

    protected $dates = ['created_at', 'updated_at'];

    // protected $appends = ['download_link'];

    public function modelable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDownloadLinkAttribute()
    {
        return route('media', ['id' => $this->id]);
    }

    function getHumanFileSizeAttribute()
    {
        $bytes = $this->size;
        $decimals = 2;

        $size = array(' B',' kB',' MB',' GB',' TB',' PB',' EB',' ZB',' YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public function getCreatedRssAttribute()
    {
        return $this->created_at->toRssString();
    }

    public function getCreatedW3cAttribute()
    {
        return $this->created_at->toW3cString();
    }

    /**
     *
     * @return \Carbon\Carbon
     */
    public function getCreatedAtHumanAttribute()
    {
        //if(app()->getLocale() === 'ar') {
        //    Carbon::setLocale('ar');
        //} else {
        //    Carbon::setLocale('en');
        //}

        return $this->created_at->diffForHumans();
    }
}
