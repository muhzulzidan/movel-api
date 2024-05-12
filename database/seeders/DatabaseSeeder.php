<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(LabelSeatCarSeeder::class);
        $this->call([
            RoleSeeder::class,
            KotaKabSeeder::class,
            TimeDepartureSeeder::class,
            UserSeeder::class,
            PassengerSeeder::class,
            DriverSeeder::class,
            CarSeeder::class,
            LabelSeatCarSeeder::class,
            StatusOrderSeeder::class,
        ]);
    }
}
