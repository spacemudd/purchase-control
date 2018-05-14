<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubtotalTotalAfterVatColumnsToPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->bigInteger('subtotal_minor')->nullable();
            $table->bigInteger('tax_amount_1_minor')->nullable();
            $table->bigInteger('total_minor')->nullable();
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
            $table->dropColumn([
                'subtotal_minor',
                'tax_amount_1_minor',
                'total_minor',
            ]);
        });
    }
}
