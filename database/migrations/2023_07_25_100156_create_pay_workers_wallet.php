<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayWorkersWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_workers_wallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pay_worker_id')->nullable()->comment('id from pay_workers table');
            $table->enum('status', array('CREDIT','DEBIT'))->comment('wallet type')->nullable();
            $table->double('amount', 10, 2)->default(0)->nullable();
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
        Schema::dropIfExists('pay_workers_wallet');
    }
}
