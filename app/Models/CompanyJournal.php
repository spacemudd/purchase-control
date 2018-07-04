<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Scottlaurent\Accounting\ModelTraits\AccountingJournal;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class CompanyJournal extends Model
{
    use AccountingJournal, NodeTrait;

    protected $fillable = ['name', 'system_account'];

    /**
     * Morph to Journal.
     *
     * @return mixed
     */
    public function journal()
    {
        return $this->morphOne(Journal::class,'morphed');
    }

    /**
     * Initialize a journal for a given model object
     *
     * @param string $currency_code
     * @return Journal
     * @throws \Exception
     */
    public function initJournal($currency_code='USD') {
        if (!$this->journal) {
            DB::table('accounting_journals')->insert([
                'currency' => $currency_code,
                'balance' => 0,
                'morphed_type' => get_class($this),
                'morphed_id' => $this->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->refresh();

            return $this->journal;
        }
        throw new \Exception('Journal already exists.');
    }
}
