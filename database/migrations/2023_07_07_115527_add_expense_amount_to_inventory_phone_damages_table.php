<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpenseAmountToInventoryPhoneDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_phone_damages', function (Blueprint $table) {
            $table->double('expense_amount', 10, 2)->default(0)->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->comment('id from order table');
        });
        Schema::dropIfExists('order_inventory_expense');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_phone_damages', function (Blueprint $table) {
            $table->dropColumn(['expense_amount','order_id']);
        });
    }
}
