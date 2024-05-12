<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
    {
         Car::create([
            'merk' => 'Toyota',
            'type' => 'SUV',
            'jenis' => 'MPV',
            'model' => 'Fortuner',
            'production_year' => 2020,
            'isi_silinder' => 2755,
            'license_plate_number' => 'B 1234 XYZ',
            'machine_number' => '1KD-FTV',
            'seating_capacity' => 7,
            'driver_id' => 1, 
        ]);

    }
}
