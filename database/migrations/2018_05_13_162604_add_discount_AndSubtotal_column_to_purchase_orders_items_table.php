<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountAndSubtotalColumnToPurchaseOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders_items', function (Blueprint $table) {
            $table->bigInteger('discount_flat_minor')->nullable();
            $table->bigInteger('subtotal_minor')->nullable();
            $table->integer('tax_rate1')->nullable();
            $table->bigInteger('tax_amount_1_minor')->nullable();
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
            $table->dropColumn([
                'discount_flat',
                'subtotal',
                'tax_rate1',
                'tax_amount_1',
            ]);
        });
    }
}
