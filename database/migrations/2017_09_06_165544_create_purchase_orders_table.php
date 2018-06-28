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

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->unsigned()->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->integer('purchase_order_main_id')->unsigned()->nullable();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->string('delivery_number')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('delivered_at')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('completed_at')->nullable();
            $table->tinyInteger('type')->nullable(); // @see \App\Models\PurchaseOrder
            $table->integer('approved_by_id')->unsigned()->nullable();
            $table->timestamps(4);
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('purchase_order_main_id')->references('id')->on('purchase_orders');
            $table->foreign('approved_by_id')->references('id')->on('users');
            /**
             * Added columns from different migrations.
             *
             * 'purchase_requisition_id'
             *
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
