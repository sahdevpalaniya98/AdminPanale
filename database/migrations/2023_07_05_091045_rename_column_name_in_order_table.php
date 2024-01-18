<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnNameInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->renameColumn('pay_workder_id', 'pay_worker_id');
            $table->renameColumn('pay_workder_payment', 'pay_worker_payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->renameColumn('pay_worker_id', 'pay_workder_id');
            $table->renameColumn('pay_worker_payment', 'pay_workder_payment');
        });
    }
}
