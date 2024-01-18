<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPhoneDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_phone_damages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id')->nullable()->comment('id from inventory table');
            $table->unsignedBigInteger('phone_damage_id')->nullable()->comment('id from phone_damages table');
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
        Schema::dropIfExists('inventory_phone_damages');
    }
}
