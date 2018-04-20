<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 100)->unique()->nullable()->comment('Assigned when the record is saved');

            $table->integer('requested_by_id')->unsigned();
            $table->foreign('requested_by_id')->references('id')->on('employees');
            $table->integer('cost_center_by_id')->unsigned();
            $table->foreign('cost_center_by_id')->references('id')->on('departments');

            $table->integer('requested_for_id')->unsigned()->nullable();
            $table->foreign('requested_for_id')->references('id')->on('employees');
            $table->integer('cost_center_for_id')->unsigned()->nullable();
            $table->foreign('cost_center_for_id')->references('id')->on('departments');

            $table->tinyInteger('status');

            $table->integer('created_by_id')->unsigned();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->integer('approved_by_id')->unsigned()->nullable();
            $table->foreign('approved_by_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::table('purchase_orders', function(Blueprint $table) {
            $table->integer('request_document_id')->unsigned()->nullable();
            $table->foreign('request_document_id')->references('id')->on('request_documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function(Blueprint $table) {
            $table->dropForeign(['request_document_id']);
        });

        Schema::dropIfExists('request_documents');
    }
}
