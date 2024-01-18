<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_expense', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id')->nullable()->comment('id from expense table');
            $table->unsignedBigInteger('inventory_id')->nullable()->comment('id from inventory table');
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
        Schema::dropIfExists('inventory_expense');
    }
}
