<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestDocumentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_document_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_document_id')->unsigned();
            $table->foreign('request_document_id')->references('id')->on('request_documents');
            $table->integer('item_template_id')->unsigned();
            $table->foreign('item_template_id')->references('id')->on('item_templates');

            // Filled from item_templates table.
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('model_number')->nullable();
            $table->string('manufacturer')->nullable();

            $table->string('description')->nullable();
            $table->decimal('qty', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_document_items');
    }
}
