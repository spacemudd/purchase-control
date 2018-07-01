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

class Employee extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

	protected $fillable = ['code', 'department_id', 'staff_type_id', 'name', 'email', 'phone', 'approver'];

	protected $appends = ['link', 'edit_link', 'approver_link', 'display_name'];

	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	public function type()
	{
	    return $this->belongsTo(StaffType::class, 'staff_type_id', 'id');
	}

	public function setNameAttribute($value)
	{
		$this->attributes['name'] = ucwords($value);
	}

	public function getDisplayNameAttribute()
	{
		return $this->code . ' - ' . $this->name;
	}

	public function getLinkAttribute()
	{
	    return route('employees.show', ['id' => $this->id]);
	}

    public function getEditLinkAttribute()
    {
        return route('employees.edit', ['id' => $this->id]);
    }

    public function getApproverLinkAttribute()
    {
        if($this->approver) {
            return route('approvers.show', ['id' => $this->id]);
        } else {
            return false;
        }
    }

    public function getFinancialAuthorityHumanAttribute()
    {
        if($this->financial_auth) {
            $currency = $this->financial_auth_currency;
            return Money::ofMinor($this->financial_auth, $currency ? $currency : 'SAR')->getAmount();
        }
    }

    public function scopeApprovers($q)
    {
        return $q->where('approver', true);
    }
}
