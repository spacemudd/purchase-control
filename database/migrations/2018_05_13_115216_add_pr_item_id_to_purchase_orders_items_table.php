<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrItemIdToPurchaseOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders_items', function (Blueprint $table) {
            $table->integer('pr_item_id')->unsigned()->nullable();
            $table->foreign('pr_item_id')->references('id')->on('purchase_requisition_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders_items', function (Blueprint $table) {
            $table->dropForeign(['pr_item_id']);
            $table->dropColumn(['pr_item_id']);
        });
    }
}
