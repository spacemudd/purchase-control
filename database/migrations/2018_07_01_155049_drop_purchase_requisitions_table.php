<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('purchase_order_purchase_requisition');

        Schema::dropIfExists('purchase_requisitions_subscribers');

        Schema::dropIfExists('purchase_requisition_simple_items');

        Schema::table('purchase_orders_items', function (Blueprint $table) {
            $table->dropForeign(['pr_item_id']);
            $table->dropColumn(['pr_item_id']);
        });

        Schema::dropIfExists('purchase_requisition_items');
        Schema::dropIfExists('purchase_requisitions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
