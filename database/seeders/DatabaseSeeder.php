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
        $this->call([
            DaySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            SpecialtySeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            ScheduleSeeder::class,
        ]);
        //Person::factory(10)->create();
        // \App\Models\User::factory(10)->create();
    }
}
