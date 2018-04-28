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

class Department extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $fillable = ['code', 'description','head_department'];

    protected $appends = ['department_human', 'link', 'edit_link'];

    public function employees()
    {
    	return $this->hasMany(Employee::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function getDepartmentHumanAttribute()
    {
    	return $this->code . ' - ' . $this->description;
    }

    public function getDisplayNameAttribute()
    {
        return $this->code . ' - ' . $this->description;
    }

    public function getLinkAttribute()
    {
        return route('departments.show', ['id' => $this->id]);
    }

    public function getEditLinkAttribute()
    {
        return route('departments.edit', ['id' => $this->id]);
    }
}
