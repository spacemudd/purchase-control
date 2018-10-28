<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function cost_center()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
