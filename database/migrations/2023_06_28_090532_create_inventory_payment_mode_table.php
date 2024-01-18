<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPaymentModeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_payment_mode', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id')->nullable()->comment('id from inventory table');
            $table->unsignedBigInteger('payment_mode_id')->nullable()->comment('id from payment_modes table');
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
        Schema::dropIfExists('inventory_payment_mode');
    }
}
