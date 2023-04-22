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
                'time_name' => 'pagi',
                'hour_start' => '06:00:00',
                'hour_end' => '11:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'time_name' => 'siang',
                'hour_start' => '11:00:00',
                'hour_end' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'time_name' => 'malam',
                'hour_start' => '17:00:00',
                'hour_end' => '23:59:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
