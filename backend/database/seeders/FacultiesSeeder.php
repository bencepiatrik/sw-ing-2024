<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\University;

class FacultiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculties = [
            'Univerzita Konštantína Filozofa v Nitre' => [
                'Fakulta prírodných vied a informatiky',
                'Fakulta sociálnych vied a zdravotníctva',
                'Fakulta stredoeurópskych štúdií',
                'Filozofická fakulta',
                'Pedagogická fakulta',
            ],
            'Univerzita sv. Cyrila a Metoda v Trnave' => [
                'Filozofická fakulta',
                'Fakulta masmediálnej komunikácie',
                'Fakulta prírodných vied',
                'Fakulta sociálnych vied',
                'Fakulta zdravotníckych vied',
            ],
            'Univerzita Mateja Bela v Banskej Bystrici' => [
                'Ekonomická fakulta',
                'Fakulta politických vied a medzinárodných vzťahov',
                'Fakulta prírodných vied',
                'Fakulta telesnej výchovy, športu a zdravia',
                'Filozofická fakulta',
                'Pedagogická fakulta',
                'Právnická fakulta',
            ],
        ];

        foreach ($faculties as $universityName => $facultyList) {
            $university = University::where('name', $universityName)->first();

            if ($university) {
                foreach ($facultyList as $facultyName) {
                    DB::table('faculties')->insert([
                        'uni_id' => $university->id,
                        'name' => $facultyName,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
