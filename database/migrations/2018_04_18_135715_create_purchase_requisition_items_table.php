<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisition_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_requisition_id')->unsigned();
            $table->foreign('purchase_requisition_id')->references('id')->on('purchase_requisitions');
            $table->integer('item_template_id')->unsigned();
            $table->foreign('item_template_id')->references('id')->on('item_templates');

            // Filled from item_templates table.
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('model_number')->nullable();
            $table->string('manufacturer')->nullable();

            $table->string('description')->nullable();
            $table->decimal('qty', 15, 2);
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
        Schema::dropIfExists('purchase_requisitions_items');
    }
}
