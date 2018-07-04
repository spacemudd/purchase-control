<?php

use App\Models\Journal;
use Illuminate\Database\Seeder;
use App\Models\CompanyJournal;
use App\Models\Ledger;

class AccountingLedgersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function() {
            $currency = 'SAR';

            $liabilityLedger = Ledger::create(['name' => 'Liability', 'type' => 'liability']);

            $aPayable = CompanyJournal::create(['name' => 'Accounts Payable', 'system_account' => true])
                        ->initJournal($currency);

            $aPayable->assignToLedger($liabilityLedger);
        });
    }
}
