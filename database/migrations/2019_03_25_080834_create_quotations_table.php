<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_request_id')->unsigned();
            $table->foreign('material_request_id')->references('id')->on('material_requests');
            $table->integer('vendor_id')->unsigned();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('cost_center_id')->unsigned();
            $table->foreign('cost_center_id')->references('id')->on('departments');
            $table->string('vendor_quotation_number');
            $table->tinyInteger('status');
            $table->timestamp('saved_at')->nullable();
            $table->integer('saved_by_id')->unsigned()->nullable();
            $table->foreign('saved_by_id')->references('id')->on('users');
            $table->timestamps(4);
            $table->unique(['vendor_id', 'vendor_quotation_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
