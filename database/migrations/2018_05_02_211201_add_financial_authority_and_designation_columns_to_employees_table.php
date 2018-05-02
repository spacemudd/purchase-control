<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinancialAuthorityAndDesignationColumnsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('approver')->default(false);
            $table->unsignedBigInteger('financial_auth')->nullable();
            $table->char('financial_auth_currency', 3)->nullable();
            $table->string('designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'approver',
                'financial_auth',
                'financial_auth_currency',
                'designation',
            ]);
        });
    }
}
