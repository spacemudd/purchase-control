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

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('support_url')->nullable();
            $table->string('support_phone')->nullable();
            $table->string('support_email')->nullable();
            $table->integer('created_by_id')->unsigned()->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps(4);
            $table->softDeletes('deleted_at', 4);
        });

        \DB::statement('ALTER TABLE pur_manufacturers AUTO_INCREMENT = 1002;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturers');
    }
}
