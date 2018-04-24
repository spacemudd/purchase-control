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

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_template_id')->unsigned();
            $table->foreign('item_template_id')->references('id')->on('item_templates');
            $table->integer('purchase_order_id')->unsigned()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->integer('manufacturer_id')->unsigned()->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors');

            $table->unsignedBigInteger('cost_minor');
            $table->boolean('physical');
            $table->string('status')->nullable(); // @see \App\Models\Item

            $table->string('manufacturer_serial_number')->nullable();
            $table->string('finance_tag_number')->nullable();
            $table->String('system_tag_number')->nullable();

            $table->timestamps(4);
            $table->softDeletes('deleted_at', 4);

            $table->index(['vendor_id']);
            $table->index(['manufacturer_serial_number']);
            $table->index(['finance_tag_number']);
            $table->index(['system_tag_number']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
