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
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('message', 'content');
        });
    }
    

public function down()
{
    Schema::table('messages', function (Blueprint $table) {
        if (Schema::hasColumn('messages', 'message')) {
            $table->renameColumn('message', 'content');
        }
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
  
};
