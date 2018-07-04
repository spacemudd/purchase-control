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
        $permissions = [
            'view-sales-taxes',
            'create-sales-taxes',
        ];

        foreach($permissions as $permission) {
            \App\Models\Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
                'type' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
