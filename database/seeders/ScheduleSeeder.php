<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Doctor;
use App\Models\OfferDays;
use App\Models\OfferSpecialty;
use App\Models\Specialty;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctors = Doctor::all();
        foreach ($doctors as $doc) {
            $spec = Specialty::all()->random(3);
            $hor = ['06:00', '13:00', '19:00', '22:00'];
            $i = 0;
            foreach ($spec as $sp) {
                $off = OfferSpecialty::create([
                    'id_specialty' => $sp->id,
                    'id_doctor' => $doc->id,
                    'time_start' => $hor[$i],
                    'time_end' => $hor[$i + 1],
                ]);
                $days = Day::all()->random(4);
                foreach ($days as $day) {
                    OfferDays::create([
                        'id_offer' => $off->id,
                        'id_day' => $day->id
                    ]);
                }
                $i++;
            }
        }
    }
}
