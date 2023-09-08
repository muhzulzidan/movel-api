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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('photo')->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('is_smoking')->nullable();
            $table->bigInteger('driver_age')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_sim')->nullable();
            $table->string('foto_stnk')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
