<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = Driver::all();
        $passengers = Passenger::all();

// Generate 50 ratings for each driver and passenger
        foreach ($drivers as $driver) {
            foreach ($passengers as $passenger) {
                for ($i = 1; $i <= 5; $i++) {
                    Rating::create([
                        'driver_id' => $driver->id,
                        'passenger_id' => $passenger->id,
                        'nilai_rating' => rand(4, 5),
                    ]);
                }
            }
        }
    }
}
