<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Document;
use App\Models\Person;
use App\Models\ResponseVerification;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('es_BO');
        for ($i = 0; $i < 10; $i++) {
            $first_name = $faker->unique()->firstName;
            $email = $first_name . '@email.com';
            $person = Person::create([
                'name' => $first_name,
                'last_name' => $faker->lastName,
                'ci' => $faker->numerify('#######'),
                'cellphone' => '7' . $faker->numerify('#######'),
                'birthday' => $faker->date,
                'sex' => $faker->randomElement(['M', 'F', 'O']),
                'email' => $email,
            ]);
            $user = User::create([
                'name' => $first_name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'id_person' => $person->id
            ]);
            $user->markEmailAsVerified();
            $doctor = Doctor::create([
                'reg_doctor' => $faker->numerify('########'),
                'id_person' => $person->id
            ]);
            $ver = Verification::create([
                'id_doctor' => $doctor->id,
            ]);
            Document::create([
                'name' => 'Curriculum',
                'url' => 'https://meditop-go.s3.us-west-2.amazonaws.com/doctors/docs/Curriculum.pdf',
                'id_doctor' => $doctor->id
            ]);
            if ($i < 5) {
                ResponseVerification::create([
                    'response' => true,
                    'detail' => 'Verificacion valida, documentos validos',
                    'id_verification' => $ver->id,
                    'id_admin' => 1
                ]);
                $ver->state = 'aceptada';
                $ver->save();
                $doctor->verified = true;
                $doctor->save();
                $user->assignRole('Doctor');
            }else if($i < 7){
                ResponseVerification::create([
                    'response' => false,
                    'detail' => 'Datos invalidados',
                    'id_verification' => $ver->id,
                    'id_admin' => 1
                ]);
                $ver->state = 'rechazada';
                $ver->save();
            }
        }
    }
}
