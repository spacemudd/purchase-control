<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseTermsType extends Model
{
    protected $fillable = ['name', 'value'];

    public function terms()
    {
        return $this->hasMany(PurchaseTerm::class, 'type_id');
    }
}
