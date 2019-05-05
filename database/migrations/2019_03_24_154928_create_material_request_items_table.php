<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_request_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_request_id')->unsigned();
            $table->foreign('material_request_id')->references('id')->on('material_requests');
            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('SET NULL');
            $table->string('description');
            $table->unsignedInteger('qty')->default(1);
            $table->timestamps(4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_request_items');
    }
}
