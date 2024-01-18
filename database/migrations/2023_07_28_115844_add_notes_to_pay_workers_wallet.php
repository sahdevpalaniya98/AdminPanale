<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesToPayWorkersWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_workers_wallet', function (Blueprint $table) {
            $table->longText('notes')->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->comment('id from order table');
            $table->unsignedBigInteger('inventory_id')->nullable()->comment('id from inventory table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pay_workers_wallet', function (Blueprint $table) {
            $table->dropColumn(['notes', 'order_id', 'inventory_id']);
        });
    }
}
