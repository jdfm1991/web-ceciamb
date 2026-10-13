<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClinicInfoSeeder::class,
            SpecialtySeeder::class,
            DoctorSeeder::class,
            DoctorScheduleSeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
        ]);
    }
}
