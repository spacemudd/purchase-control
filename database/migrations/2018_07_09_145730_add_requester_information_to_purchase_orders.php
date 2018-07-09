<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequesterInformationToPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->integer('requested_by_employee_id')->unsigned()->nullable();
            $table->foreign('requested_by_employee_id')->references('id')->on('employees');
            $table->integer('requested_for_employee_id')->unsigned()->nullable();
            $table->foreign('requested_for_employee_id')->references('id')->on('employees');
            $table->integer('cost_center_id')->unsigned()->nullable();
            $table->foreign('cost_center_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['requested_by_employee_id']);
            $table->dropForeign(['requested_for_employee_id']);
            $table->dropForeign(['cost_center_id']);

            $table->dropColumn(['requested_by_employee_id', 'requested_for_employee_id', 'cost_center_id']);
        });
    }
}
