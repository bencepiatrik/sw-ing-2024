<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        $publications = [
            [
                'conference_id' => 1,
                'title' => 'Analýza trendov v IT sektore',
                'state' => 'Prijatá',
                'content' => 'Táto práca analyzuje aktuálne trendy v oblasti informačných technológií...',
            ],
            [
                'conference_id' => 1,
                'title' => 'Vývoj cloudových služieb',
                'state' => 'Prijatá',
                'content' => 'Práca sa zaoberá vývojom a optimalizáciou cloudových technológií...',
            ],
            [
                'conference_id' => 2,
                'title' => 'Aplikácia blockchain technológie',
                'state' => 'Prezentovaná',
                'content' => 'Štúdia sa venuje možnostiam aplikácie blockchainu mimo finančného sektora...',
            ],
            [
                'conference_id' => 2,
                'title' => 'Robotika a umelá inteligencia',
                'state' => 'Prezentovaná',
                'content' => 'Práca analyzuje nové trendy v oblasti robotiky a AI...',
            ],
            [
                'conference_id' => 3,
                'title' => 'Strojové učenie v praxi',
                'state' => 'Prijatá',
                'content' => 'Práca sa zaoberá praktickými aplikáciami strojového učenia v priemysle...',
            ],
            [
                'conference_id' => 3,
                'title' => 'Analýza veľkých dát',
                'state' => 'Prijatá',
                'content' => 'Táto práca skúma prístupy k analýze big data...',
            ],
            [
                'conference_id' => 3,
                'title' => 'Predikčné modely v Data Science',
                'state' => 'Prijatá',
                'content' => 'Práca sa zameriava na použitie predikčných modelov v oblasti Data Science...',
            ],
            [
                'conference_id' => 4,
                'title' => 'Prístupy k ochrane údajov',
                'state' => 'Prijatá',
                'content' => 'Práca analyzuje moderné metódy ochrany údajov v kyberpriestore...',
            ],
        ];

        foreach ($publications as $publication) {
            // Skontrolujeme, či publikácia už existuje
            if (!DB::table('publications')
                ->where('conference_id', $publication['conference_id'])
                ->where('title', $publication['title'])
                ->exists()) {
                DB::table('publications')->insert([
                    'conference_id' => $publication['conference_id'],
                    'title' => $publication['title'],
                    'state' => $publication['state'],
                    'content' => $publication['content'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
