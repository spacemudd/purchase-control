<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tax_name');
            $table->string('abbreviation', 10);
            $table->decimal('current_tax_rate', 16, 4);
            $table->string('description')->nullable();
            $table->string('tax_number')->nullable()->comment('The company tax number');
            $table->boolean('show_tax_number_on_invoices');
            $table->boolean('is_recoverable');
            $table->boolean('is_compound');
            $table->integer('tax_account_id')->unsigned();
            $table->foreign('tax_account_id')->references('id')->on('company_journals');
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
        Schema::dropIfExists('sales_taxes');
    }
}
