<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UniversitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $universities = [
            'Univerzita Konštantína Filozofa v Nitre',
            'Univerzita sv. Cyrila a Metoda v Trnave',
            'Univerzita Mateja Bela v Banskej Bystrici',
        ];

        foreach ($universities as $university) {
            DB::table('universities')->insert([
                'name' => $university,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
