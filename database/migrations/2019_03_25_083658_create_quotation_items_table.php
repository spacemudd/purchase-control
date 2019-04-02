<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->unsigned();
            $table->foreign('quotation_id')->references('id')->on('quotations');
            $table->integer('vendor_id')->unsigned();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('material_request_item_id')->unsigned()->nullable();
            $table->foreign('material_request_item_id')->references('id')->on('material_requests_items');
            $table->string('description');
            $table->integer('qty')->default(1);
            $table->integer('unit_price');
            $table->integer('vat')->default(0);
            $table->integer('total_price_ex_vat');
            $table->integer('total_price_inc_vat');
            $table->timestamps(4);
            $table->softDeletes('deleted_at', 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_items');
    }
}
