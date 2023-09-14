<?php

namespace Database\Seeders;

use App\Models\LabelSeatCar;
use Illuminate\Database\Seeder;

class LabelSeatCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Data awal untuk setiap mobil
        LabelSeatCar::create([
            'car_id' => 1,
            'label_seat' => 'Sopir',
        ]);

        // Membuat data untuk label kursi A - F
        for ($i = 1; $i <= 6; $i++) {
            LabelSeatCar::create([
                'label_seat' => chr(64 + $i),
                'is_filled' => 0,
                'car_id' => 1,
            ]);
        }

    }
}
