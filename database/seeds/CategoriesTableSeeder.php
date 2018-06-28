<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create([
            'name' => 'End User Devices',
            'children' => [
                ['name' => 'Desktop'],
                ['name' => 'Laptop'],
                ['name' => 'Screen'],
                ['name' => 'Scanner'],
                ['name' => 'Printer'],
                ['name' => 'Barcode Reader'],
                ['name' => 'Multifunction Device'],
            ],
        ]);
    }
}
