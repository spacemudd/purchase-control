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

class ReferenceTypesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('reference_types')->insert([
            [
                'name' => 'Job Order Number',
                'description' => null,
            ],
            [
                'name' => 'Service Call Number',
                'description' => null,
            ],
            [
                'name' => 'Others',
                'description' => null,
            ],
       ]);
	}
}
