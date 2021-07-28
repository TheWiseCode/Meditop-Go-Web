<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Cardiología', 'Geriatría', 'Medicina General', 'Neumología', 'Pediatría', 'Psicología',
            'Traumatología', 'Oftalmología', 'Urología'
        ];
        foreach ($data as $d){
            Specialty::create(['name' => $d]);
        }
    }
}
