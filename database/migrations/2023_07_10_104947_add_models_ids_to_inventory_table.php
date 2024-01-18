<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModelsIdsToInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->comment('id from phone_brand table');
            $table->unsignedBigInteger('series_id')->nullable()->comment('id from phone_series table');
            $table->unsignedBigInteger('model_id')->nullable()->comment('id from phone_model table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn(['brand_id', 'series_id', 'model_id']);
        });
    }
}
