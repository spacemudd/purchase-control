<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnabledToPurchaseTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_terms', function (Blueprint $table) {
            $table->boolean('enabled')->nullable();
        });

        \App\Model\PurchaseTerm::where('id', '>', 0)
            ->update([
                'enabled' => false,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_terms', function (Blueprint $table) {
            $table->dropColumn('enabled');
        });
    }
}
