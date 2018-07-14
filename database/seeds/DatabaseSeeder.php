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

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(StaffTypesTableSeeder::class);
        $this->call(ReferenceTypesTableSeeder::class);

        $this->call(PermissionsSecondGroupTableSeeder::class);
        $this->call(PurchaseTermsTableSeeder::class);

        $this->call(CategoriesTableSeeder::class);

        $this->call(SalesTaxesPermissionsSeeder::class);

        // Faker.
        //factory(\App\Models\Vendor::class, 2)->create();
        //factory(\App\Models\Employee::class, 2)->create();
    }
}
