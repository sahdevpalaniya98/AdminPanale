<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDamages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_damages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phone_damage_id')->nullable()->comment('id from phone damages table');
            $table->unsignedBigInteger('order_id')->nullable()->comment('id from order table');
            $table->double('amount', 10, 2)->default(0);
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
        Schema::dropIfExists('order_damages');
    }
}
