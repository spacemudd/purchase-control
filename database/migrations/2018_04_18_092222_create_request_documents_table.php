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

            $table->string('purpose')->nullable();
            $table->string('itam_remarks')->nullable();
            $table->integer('purchase_order_id')->unsigned()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');

            $table->integer('recommended_by_id')->nullable()->unsigned();
            $table->foreign('recommended_by_id')->references('id')->on('employees');

            $table->integer('f_auth_by_id')->unsigned()->nullable()->comment('Financial authority by ID');
            $table->foreign('f_auth_by_id')->references('id')->on('employees');

            $table->integer('checked_by_id')->unsigned()->nullable();
            $table->foreign('checked_by_id')->references('id')->on('employees');

            $table->integer('head_of_itam_id')->unsigned()->nullable();
            $table->foreign('head_of_itam_id')->references('id')->on('employees');

            $table->integer('po_prepared_by_id')->unsigned()->nullable();
            $table->foreign('po_prepared_by_id')->references('id')->on('employees');

            $table->integer('purchasing_head_id')->unsigned()->nullable();
            $table->foreign('purchasing_head_id')->references('id')->on('employees');

            $table->integer('created_by_id')->unsigned();
            $table->foreign('created_by_id')->references('id')->on('users');
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
        Schema::dropIfExists('request_documents');
    }
}
