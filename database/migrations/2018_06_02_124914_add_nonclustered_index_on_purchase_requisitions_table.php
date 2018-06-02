<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNonclusteredIndexOnPurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            if(env('DB_DATABASE') === 'sqlsrv') {
                $table->dropUnique(['number']);
                \DB::statement('CREATE UNIQUE NONCLUSTERED INDEX purchase_requisitions_number_uq ON dbo.pur_purchase_requisitions(number) WHERE number IS NOT NULL;');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            if(env('DB_DATABASE') === 'sqlsrv') {
                \DB::statement('DROP INDEX purchase_requisitions_number_uq ON dbo.pur_purchase_requisitions;');
                $table->unique(['number']);
            }
        });
    }
}
