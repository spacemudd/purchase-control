<?php

use Illuminate\Database\Seeder;

class SalesTaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function() {
            $companyJournal = \App\Models\CompanyJournal::create([
                'name' => 'VAT',
                'system_account' => true,
            ]);

            $companyJournal->initJournal('SAR');

            \App\Models\SalesTax::create([
                'tax_name' => 'VAT',
                'abbreviation' => 'VAT',
                'current_tax_rate' => 5,
                'description' => 'value-added tax',
                'tax_number' => '',
                'show_tax_number_on_invoices' => false,
                'is_recoverable' => false,
                'is_compound' => false,
                'tax_account_id' => $companyJournal->id,
            ]);
        });
    }
}
