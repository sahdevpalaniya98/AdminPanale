<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id')->nullable()->comment('id from inventory table');
            $table->unsignedBigInteger('order_id')->nullable()->comment('id from order table');
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
        Schema::dropIfExists('order_inventory_items');
    }
}
