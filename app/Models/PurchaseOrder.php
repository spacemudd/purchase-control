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

use App\Traits\HasFiles;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class PurchaseOrder extends Model implements AuditableContract
{
    use Auditable, HasFiles;

    /**
     * Multi vendors, 1 employee purchase orders' sub-POs will increment as:
     *     PO-2282
     *          PO-2282-A (subPO)
     *          PO-2282-B (subPO)
     *
     * Multi employees, 1 vendor sub-POs will increment as:
     *     PO-2282
     *          PO-2282-1 (subPO)
     *          PO-2282-2 (subPO)
     */

    const TYPE_MULTI_VENDORS = 0;
    const TYPE_MULTI_EMPLOYEES = 1;

    /**
     * Purchase order statuses.
     */
    const NEW = 0;
    const SAVED = 1;
    const APPROVED = 2;
    const VOID = 3;

    protected $fillable = [
        'department_id',
        'employee_id',
        'vendor_id',
        'number',
        'date',
        'delivery_number',
        'delivery_date',
        'delivered_at',
        'status',
        'completed_at',
        'type',
        'approved_by_id',
        'shipping_address_id',
        'billing_address_id',
        'shipping_address_json',
        'billing_address_json',
        'currency',
    ];

    protected $dates = ['date', 'delivery_date'];

    protected $appends = ['status_human', 'date_human', 'delivery_date_human', 'link'];

    public function items()
    {
        return $this->hasMany(PurchaseOrdersItem::class);
    }

    public function service_items()
    {
        return $this->hasMany(PurchaseOrdersLine::class);
    }

    public function employee()
    {
    	return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function department()
    {
    	return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function vendor()
    {
    	return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function getStatusHumanAttribute()
    {
        switch($this->status) {
            case self::NEW:
                return 'draft';
            case self::APPROVED:
                return 'approved';
            case self::VOID:
                return 'void';

            default:
                return '';
        }
    }

    public function getDateHumanAttribute()
    {
        return optional($this->date)->format('d-m-Y');
    }

    public function getDeliveryDateHumanAttribute()
    {
        return optional($this->delivery_date)->format('d-m-Y');
    }

    public function getIsDraftAttribute()
    {
        return $this->status == 'draft';
    }

    public function getIsCommittedAttribute()
    {
        return $this->status == 'committed';
    }

    public function getIsVoidAttribute()
    {
        return $this->status == 'void';
    }

    public function getLinkAttribute()
    {
        return route('purchase-orders.show', ['id' => $this->id]);
    }

    public function scopeDraft($q)
    {
        $q->where('status', self::NEW);
    }

    public function scopeCommitted($q)
    {
        $q->where('status', self::SAVED);
    }

    public function scopeVoid($q)
    {
        $q->where('status', self::VOID);
    }
}
