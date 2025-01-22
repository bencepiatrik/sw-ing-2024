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
                'title' => 'Analysis of IT Trends',
                'abstract' => 'This paper analyzes current trends in the field of information technology.',
                'keywords' => 'IT, trends, analysis',
                'user_id' => 1,
                'conference_id' => 1,
                'files' => null,
                'status' => 'prijatá',
            ],
            [
                'title' => 'Development of Cloud Services',
                'abstract' => 'The paper discusses the development and optimization of cloud technologies.',
                'keywords' => 'cloud, optimization, technology',
                'user_id' => 2,
                'conference_id' => 1,
                'files' => null,
                'status' => 'odovzdaná',
            ],
            [
                'title' => 'Blockchain Technology Applications',
                'abstract' => 'A study of blockchain applications beyond the financial sector.',
                'keywords' => 'blockchain, technology, application',
                'user_id' => 3,
                'conference_id' => 2,
                'files' => null,
                'status' => 'vytvorená',
            ],
            [
                'title' => 'Robotics and Artificial Intelligence',
                'abstract' => 'This paper analyzes new trends in robotics and AI.',
                'keywords' => 'robotics, AI, trends',
                'user_id' => 4,
                'conference_id' => 2,
                'files' => null,
                'status' => 'prijatá',
            ],
            [
                'title' => 'Machine Learning in Practice',
                'abstract' => 'This paper discusses practical applications of machine learning in industry.',
                'keywords' => 'machine learning, industry, applications',
                'user_id' => 3,
                'conference_id' => 3,
                'files' => null,
                'status' => 'odmietnutá',
            ],
        ];

        foreach ($publications as $publication) {
            DB::table('publications')->insert([
                'title' => $publication['title'],
                'abstract' => $publication['abstract'],
                'keywords' => $publication['keywords'],
                'user_id' => $publication['user_id'],
                'conference_id' => $publication['conference_id'],
                'files' => $publication['files'],
                'status' => $publication['status'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
