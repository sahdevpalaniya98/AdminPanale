<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->comment('id from customer table');
            $table->unsignedBigInteger('employee_id')->nullable()->comment('id from users table');
            $table->string('phone_name')->nullable();
            $table->longText('phone_sickw_details')->nullable();
            $table->double('purchase_price', 10, 2)->default(0)->nullable();
            $table->boolean('is_sold')->default(false)->nullable();
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
        Schema::dropIfExists('inventory');
    }
}
