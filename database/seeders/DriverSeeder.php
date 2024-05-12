<?php

namespace Database\Seeders;
use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Driver::create([
            'user_id' => 1, // Make sure a User with id 1 exists
            'address' => '123 Main St',
            'photo' => 'driver1.jpg', // Make sure this photo exists in /storage/photos/
            'is_smoking' => false,
            'driver_age' => 30,
            'no_ktp' => '1234567890',
            'foto_ktp' => 'ktp1.jpg', // Make sure this photo exists in /storage/photos/
            'foto_sim' => 'sim1.jpg', // Make sure this photo exists in /storage/photos/
            'foto_stnk' => 'stnk1.jpg', // Make sure this photo exists in /storage/photos/
        ]);
    }
}
