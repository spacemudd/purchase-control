<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'body',
        'user_id',
    ];

    protected $hidden = ['notable_type'];

    protected $appends = ['created_rss', 'created_w3c', 'created_at_human'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get all of te owning notable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
        if(app()->getLocale() === 'ar') {
            Carbon::setLocale('ar');
        } else {
            Carbon::setLocale('en');
        }

        return $this->created_at->diffForHumans();
    }
}
