<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profs = ['Administrador de empresas', 'Auxiliar administrativo', 'Contador', 'Encargado de marketing'];
        foreach ($profs as $p) {
            Profession::create([
                'name' => $p
            ]);
        }
    }
}
