<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date', 4);
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->integer('cost_center_id')->unsigned();
            $table->foreign('cost_center_id')->references('id')->on('departments');
            $table->integer('created_by_id')->unsigned();
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by_id')->unsigned()->nullable();
            $table->foreign('approved_by_id')->references('id')->on('users');
            $table->string('number')->unique();
            $table->tinyInteger('status');
            $table->softDeletes('deleted_at', 4);
            $table->timestamps(4);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_requests');
    }
}
