<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(12345678),
            'role_id' => 1,
            'no_hp' => '085245786543'
        ]);
        // Driver user
        DB::table('users')->insert([
            'name' => 'Driver',
            'email' => 'driver@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 2, // Replace with the actual role_id for drivers
            'no_hp' => '085245786544'
        ]);

        // Passenger user
        DB::table('users')->insert([
            'name' => 'Passenger',
            'email' => 'passenger@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 3, // Replace with the actual role_id for passengers
            'no_hp' => '085245786545'
        ]);

    }
}
