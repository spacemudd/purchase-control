<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTax extends Model
{
    protected $fillable = [
        'tax_name',
        'abbreviation',
        'current_tax_rate',
        'description',
        'tax_number',
        'show_tax_number_on_invoices',
        'is_recoverable',
        'is_compound',
        'tax_account_id',
    ];

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute()
    {
        return $this->abbreviation . '('. number_format($this->current_tax_rate, 2) .'%)';
    }

    public function company_journal()
    {
        return $this->belongsTo(CompanyJournal::class, 'tax_account_id');
    }
}
