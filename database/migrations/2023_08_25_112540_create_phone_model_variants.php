<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneModelVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_model_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phone_model_id')->nullable()->comment('id from phone model table');
            $table->unsignedBigInteger('variant_id')->nullable()->comment('id from variant table');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_model_variants');
    }
}