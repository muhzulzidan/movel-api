<?php

namespace Database\Seeders;

use App\Models\Passenger;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PassengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Passenger::create([
            'user_id' => 1, // Make sure a User with id 1 exists
            'address' => '123 Main St',
            'photo' => 'passenger1.jpg', // Make sure this photo exists in /storage/photos/
            'gender' => 'Laki-laki',
        ]);
    }
}
