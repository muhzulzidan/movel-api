<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('driver_departure_id');
            $table->unsignedBigInteger('status_order_id');
            $table->integer('price_order');

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('driver_departure_id')->references('id')->on('driver_departures')->restrictOnDelete();
            $table->foreign('status_order_id')->references('id')->on('status_orders')->restrictOnDelete();
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
        Schema::dropIfExists('orders');
    }
};
