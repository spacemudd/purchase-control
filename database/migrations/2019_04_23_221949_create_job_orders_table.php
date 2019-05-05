<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_order_number');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('cost_center_id');
            $table->string('ext');
            $table->string('requested_through_type');
            $table->text('job_description');
            $table->string('status');
            $table->string('remark');
            $table->date('date');
            $table->dateTime('time_start');
            $table->dateTime('time_end');
            $table->unsignedInteger('location_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('cost_center_id')->references('id')->on('departments');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_orders');
    }
}
