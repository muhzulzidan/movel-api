<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverDepartureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('driver_departures')->insert([
            [
                'driver_id' => 1,
                'kota_asal_id' => 6,
                'kota_tujuan_id' => 18,
                'date_departure' => '2023-04-25',
                'time_departure' => "18:30",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
