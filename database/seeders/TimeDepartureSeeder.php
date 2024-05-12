<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeDepartureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('time_departures')->insert([
            [
                'time_name' => 'Pagi',
                'hour_start' => '05:00',
                'hour_end' => '11:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'time_name' => 'Siang',
                'hour_start' => '11:00',
                'hour_end' => '15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'time_name' => 'Sore',
                'hour_start' => '15:00',
                'hour_end' => '18:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'time_name' => 'Malam',
                'hour_start' => '18:00',
                'hour_end' => '23:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'time_name' => 'Tengah Malam',
                'hour_start' => '23:00',
                'hour_end' => '05:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
