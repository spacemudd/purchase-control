<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorBank extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'address',
        'beneficiary_name',
        'account_number',
        'iban_code',
        'swift_code',
        'sort_code',
        'currency',
    ];
}
