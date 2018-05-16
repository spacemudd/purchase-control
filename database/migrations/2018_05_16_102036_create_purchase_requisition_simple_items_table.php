<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequisitionSimpleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisition_simple_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_requisition_id')->unsigned();
            $table->foreign('purchase_requisition_id', 'pr_si_prs_fk')->references('id')->on('purchase_requisitions');
            $table->string('description');
            $table->float('qty', 15, 2);
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
        Schema::dropIfExists('purchase_requisition_simple_items');
    }
}
