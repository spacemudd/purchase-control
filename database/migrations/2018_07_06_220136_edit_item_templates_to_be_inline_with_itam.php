<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditItemTemplatesToBeInlineWithItam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_templates', function (Blueprint $table) {
            $table->renameColumn('name', 'description');
            $table->integer('item_template_type_id')->unsigned()->nullable();
            $table->foreign('item_template_type_id')->references('id')->on('item_template_types');
        });

        Schema::table('item_templates', function (Blueprint $table) {
            $table->renameColumn('model_number', 'model_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_templates', function (Blueprint $table) {
            $table->renameColumn('description', 'name');
            $table->dropForeign(['item_template_type_id']);
            $table->dropColumn('item_template_type_id');
        });

        Schema::table('item_templates', function (Blueprint $table) {
            $table->renameColumn('model_details', 'model_number');
        });
    }
}
