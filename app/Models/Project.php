<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'location',
        'cost_center_id',
    ];

    public function cost_center()
    {
        return $this->belongsTo(Department::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->location . ' - Cost Center: ' . $this->cost_center->code;
    }
}
