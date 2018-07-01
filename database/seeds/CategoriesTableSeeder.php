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
            'name' => 'Communication Devices',
            'children' => [
                ['name' => 'Routers'],
                ['name' => 'Switches'],
                ['name' => 'Phone Sets'],
                ['name' => 'PABX'],
                ['name' => 'Conference Set'],
                ['name' => 'Cards'],
                ['name' => 'Other Comms Devices'],
                ['name' => 'Comms Rack'],
            ],
            'name' => 'Systems Devices',
            'children' => [
                ['name' => 'Servers'],
                ['name' => 'Rack for Server'],
                ['name' => 'Components for Servers'],
                ['name' => 'Other Systems Device'],
            ],
            'name' => 'Accessories',
            'children' => [
                ['name' => 'Keyboard'],
                ['name' => 'Mouse'],
                ['name' => 'Cables'],
            ],
            'name' => 'Other Devices',
            'children' => [
                ['name' => 'Software'],
                ['name' => 'Other Devices'],
            ],
        ]);
    }
}
