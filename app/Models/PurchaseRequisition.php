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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PurchaseRequisition extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    const UNSET = -1;
    const DRAFT = 0;
    const SAVED = 1;
    const APPROVED = 2;
    const REJECTED = 3;
    const VOID = 4;

    protected $guarded = ['id'];

    protected $appends = ['status_name', 'link'];

    public function getStatusNameAttribute()
    {
        switch($this->status) {
            case self::UNSET:
                return 'unset';
                break;
            case self::DRAFT:
                return 'draft';
                break;
            case self::SAVED:
                return 'saved';
                break;
            case self::APPROVED:
                return 'approved';
                break;
            case self::REJECTED:
                return 'rejected';
                break;

            default:
                return '';
                break;
        }
    }

    public function getLinkAttribute()
    {
        return route('purchase-requisitions.show', ['id' => $this->id]);
    }

    public function requested_by()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cost_center_by()
    {
        return $this->belongsTo(Department::class);
    }

    public function requested_for()
    {
        return $this->belongsTo(Employee::class);
    }

    public function recommended_by()
    {
        return $this->belongsTo(Employee::class);
    }


    public function cost_center_for()
    {
        return $this->belongsTo(Department::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase_requisition_items()
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }

    public function simple_items()
    {
        return $this->hasMany(PurchaseRequisitionSimpleItem::class);
    }

    /**
     * Returns the person who clicked 'approved by' in the system.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approved_by()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Returns the financial authority (person) who approved the requisition.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function f_auth_by()
    {
        return $this->belongsTo(Employee::class);
    }

    public function checked_by()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Returns the ITAM manager who approved the requisition.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function head_of_itam()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get all the notes owned by this requisition.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * Get all the media files associated with this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|\App\Models\User[]
     */
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'purchase_requisitions_subscribers', 'purchase_requisition_id', 'user_id');
    }

    public function getIsDraftAttribute()
    {
        return $this->status == self::DRAFT;
    }

    public function getCanAddItemsAttribute()
    {
        return $this->status == self::DRAFT || $this->status == self::UNSET;
    }

    public function getIsSavedAttribute()
    {
        return $this->status == self::SAVED;
    }

    public function getHasBeenSavedAttribute()
    {
        return ! ($this->status === self::DRAFT || $this->status === self::UNSET);
    }

    public function getIsApprovedAttribute()
    {
        return $this->status == self::APPROVED;
    }

    public function scopeDraft($q)
    {
        return $q->where('status', self::DRAFT);
    }

    public function scopeSaved($q)
    {
        return $q->where('status', self::SAVED);
    }

    public function scopeVoid($q)
    {
        return $q->where('status', self::VOID);
    }
}
