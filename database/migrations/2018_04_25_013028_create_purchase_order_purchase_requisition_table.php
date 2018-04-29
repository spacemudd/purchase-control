<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderPurchaseRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Pivot.
        Schema::create('purchase_order_purchase_requisition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id')->unsigned();
            $table->foreign('purchase_order_id', 'po_id_foreign')->references('id')->on('purchase_orders');
            $table->integer('purchase_requisition_id')->unsigned();
            $table->foreign('purchase_requisition_id', 'pr_id_foreign')->references('id')->on('purchase_requisitions');
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
        Schema::dropIfExists('purchase_order_purchase_requisition');
    }
}
