<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $person = Person::create([
            'name' => 'Willy',
            'last_name' => 'Vargas',
            'ci' => '11341907',
            'cellphone' => '75337753',
            'birthday' => '2000-08-07',
            'sex' => 'M',
            'email' => 'willy4k@gmail.com',
        ]);
        $user = User::create([
            'name' => 'willy',
            'email' => 'willy4k@gmail.com',
            'password' => Hash::make('will3148'),
            'id_person' => $person->id,
            'email_verified_at' => Carbon::now()
        ]);
        Admin::create([
            'id_person' => $person->id,
            'profession' => 'programmer',
            'owner' => true
        ]);
    }
}
