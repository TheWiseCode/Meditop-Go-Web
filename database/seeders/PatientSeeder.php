<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('es_BO');
        for($i = 0;$i < 5;$i++) {
            $first_name = $faker->unique()->firstName;
            $email = $first_name . '_pt@email.com';
            $person = Person::create([
                'name' => $first_name,
                'last_name' => $faker->lastName,
                'ci' => $faker->numerify('#######'),
                'cellphone' => '6' . $faker->numerify('#######'),
                'birthday' => $faker->date,
                'sex' => $faker->randomElement(['M', 'F', 'O']),
                'email' => $email,
            ]);
            Patient::create([
                'id_person' => $person->id,
                'blood_type' => $faker->randomElement(['O+', 'A+', 'A-', 'AB-', 'AB+', 'O-']),
                'allergy' => $faker->text(50),
            ]);
            User::create([
                'name' => $first_name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'id_person' => $person->id
            ])->markEmailAsVerified();
        }
    }
}
