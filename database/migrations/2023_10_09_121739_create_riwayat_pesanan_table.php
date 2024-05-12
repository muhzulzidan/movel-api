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
        Schema::create('riwayat_pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('driver')->nullable();
            $table->string('passenger')->nullable();
            $table->string('total_seats_ordered')->nullable();
            $table->string('order_date')->nullable();
            $table->string('departure_date')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('status')->nullable();
            $table->string('harga')->nullable();
            $table->string('rating')->nullable();
            $table->string('biaya_admin')->nullable();
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
        Schema::dropIfExists('riwayat_pesanan');
    }
};
