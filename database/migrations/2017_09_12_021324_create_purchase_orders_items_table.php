 <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id')->unsigned();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('SET NULL');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('manufacturer_id')->unsigned()->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->string('manufacturer_serial_number')->nullable();
            $table->string('system_tag_number')->nullable();
            $table->string('finance_tag_number')->nullable();
            $table->string('rfid')->nullable();
            $table->unsignedBigInteger('unit_price_minor');
            $table->decimal('qty', 15, 2);
            $table->unsignedBigInteger('total_minor');
            $table->date('warranty_expires_at')->nullable();
            $table->datetime('received_at', 4)->nullable();
            $table->integer('received_by_id')->unsigned()->nullable();
            $table->foreign('received_by_id')->references('id')->on('users');
            $table->timestamps(4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders_items');
    }
}
