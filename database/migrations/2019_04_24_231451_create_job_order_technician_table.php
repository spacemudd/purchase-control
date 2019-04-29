<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOrderTechnicianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_technician', function (Blueprint $table) {
            $table->unsignedInteger('job_order_id');
            $table->unsignedInteger('technician_id');

            $table->foreign('job_order_id')->references('id')->on('job_orders');
            $table->foreign('technician_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_technician');
    }
}
