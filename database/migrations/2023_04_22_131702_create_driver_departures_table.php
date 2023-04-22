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
        Schema::create('driver_departures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->unique();
            $table->unsignedBigInteger('kota_asal_id');
            $table->unsignedBigInteger('kota_tujuan_id');
            $table->date('date_departure');
            $table->unsignedBigInteger('time_departure_id');

            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('kota_asal_id')->references('id')->on('kota_kabs');
            $table->foreign('kota_tujuan_id')->references('id')->on('kota_kabs');
            $table->foreign('time_departure_id')->references('id')->on('time_departures');

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
        Schema::dropIfExists('driver_departures');
    }
};
