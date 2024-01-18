<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id')->nullable()->comment('id from buyers table');
            $table->unsignedBigInteger('pay_workder_id')->nullable()->comment('id from pay_workers table');
            $table->unsignedBigInteger('payment_mode_id')->nullable()->comment('id from payment_mode table');
            $table->double('pay_workder_payment', 10, 2)->default(0)->nullable();
            $table->double('sell_amount', 10, 2)->default(0)->nullable();
            $table->enum('order_status', ['in-progress', 'complete'])->default('in-progress')->nullable();
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
        Schema::dropIfExists('order');
    }
}
