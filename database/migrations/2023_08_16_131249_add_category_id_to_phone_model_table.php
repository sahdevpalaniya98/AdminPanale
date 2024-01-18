<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToPhoneModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phone_models', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('brand_id')->nullable()->comment('id from category table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phone_models', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
