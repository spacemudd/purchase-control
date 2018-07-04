<?php

use Illuminate\Database\Seeder;

class SalesTaxesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Permission::insert([
            [
                'name' => 'view-sales-taxes',
                'guard_name' => 'web',
                'type' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
