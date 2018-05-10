<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Address extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'type',
        'location',
        'department',
        'contact_name',
        'phone',
        'name',
        'email',
    ];

    const SHIPPING = 0;
    const BILLING = 1;

    public function scopeShipping($q)
    {
        $q->where('type', self::SHIPPING);
    }

    public function scopeBilling($q)
    {
        $q->where('type', self::BILLING);
    }

    public function getTypeHumanAttribute()
    {
        switch($this->type) {
            case self::SHIPPING:
                return 'Shipping Address';
                break;
            case self::BILLING:
                return 'Billing Address';
                break;
            default:
                return;
        }
    }
}
