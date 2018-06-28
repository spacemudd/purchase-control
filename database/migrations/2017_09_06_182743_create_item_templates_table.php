<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('model_number')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('manufacturer_id')->unsigned()->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('SET NULL');
            $table->unsignedInteger('eol')->nullable();
            $table->unsignedBigInteger('default_unit_price_minor')->nullable();
            $table->softDeletes('deleted_at', 4);
            $table->timestamps(4);

            $table->index(['code', 'model_number', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_templates');
    }
}
