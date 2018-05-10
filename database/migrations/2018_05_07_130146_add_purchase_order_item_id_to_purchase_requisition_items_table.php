<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseOrderItemIdToPurchaseRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_requisition_items', function (Blueprint $table) {
            $table->integer('purchase_orders_item_id')->unsigned()->nullable();
            $table->foreign('purchase_orders_item_id')->references('id')->on('purchase_orders_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_requisition_items', function (Blueprint $table) {
            $table->dropForeign(['purchase_orders_item_id']);
            $table->dropColumn(['purchase_orders_item_id']);
        });
    }
}
