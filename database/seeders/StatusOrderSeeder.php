<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_orders')->insert([
            [
                'status_label' => 0,
                'status_name' => 'Pesanan Ditolak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_label' => 1,
                'status_name' => 'Dipesan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_label' => 2,
                'status_name' => 'Pesanan Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
