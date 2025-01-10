<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conferences = [
            [
                'name' => 'Informatické trendy 2025',
                'year' => 2025,
                'type' => 'Národná',
                'department_id' => 34,
                'expiration_date' => Carbon::create(2025, 12, 31),
            ],
            [
                'name' => 'Technológie budúcnosti',
                'year' => 2025,
                'type' => 'Medzinárodná',
                'department_id' => 34,
                'expiration_date' => Carbon::create(2026, 6, 30),
            ],
            [
                'name' => 'Data Science a AI',
                'year' => 2024,
                'type' => 'Odborná',
                'department_id' => 34,
                'expiration_date' => Carbon::create(2025, 3, 31),
            ],
            [
                'name' => 'Kybernetická bezpečnosť',
                'year' => 2023,
                'type' => 'Medzinárodná',
                'department_id' => 34,
                'expiration_date' => Carbon::create(2024, 11, 30),
            ],
            [
                'name' => 'Programovacie jazyky a nástroje',
                'year' => 2025,
                'type' => 'Národná',
                'department_id' => 34,
                'expiration_date' => Carbon::create(2025, 9, 15),
            ],
        ];

        foreach ($conferences as $conference) {
            DB::table('conferences')->insert([
                'name' => $conference['name'],
                'year' => $conference['year'],
                'type' => $conference['type'],
                'department_id' => $conference['department_id'],
                'expiration_date' => $conference['expiration_date'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
