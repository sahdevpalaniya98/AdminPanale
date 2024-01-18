<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnCancelReasonColumnToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `order` MODIFY COLUMN `order_status` ENUM('in-progress', 'complete', 'return', 'cancel') NOT NULL DEFAULT 'in-progress'");
        Schema::table('order', function (Blueprint $table) {
            $table->longText('return_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `order` MODIFY COLUMN `order_status` ENUM('in-progress', 'complete') NOT NULL DEFAULT 'in-progress'");
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('return_reason');
        });
    }
}
