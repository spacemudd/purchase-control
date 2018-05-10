<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressesToPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->integer('shipping_address_id')->unsigned()->nullable();
            $table->foreign('shipping_address_id')->references('id')->on('addresses');
            $table->integer('billing_address_id')->unsigned()->nullable();
            $table->foreign('billing_address_id')->references('id')->on('addresses');
            $table->string('shipping_address_json', 1000)->nullable();
            $table->string('billing_address_json', 1000)->nullable();
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
            $table->dropForeign(['shipping_address_id', 'billing_address_id']);
            $table->dropColumn([
                'shipping_address_id',
                'billing_address_id',
                'shipping_address_json',
                'billing_address_json',
            ]);
        });
    }
}
