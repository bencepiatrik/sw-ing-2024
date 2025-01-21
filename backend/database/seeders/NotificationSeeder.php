<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'type' => 'Poziadanie o pracoviska',
                'user_id' => 1,
                'data' => json_encode([
                    'university_id' => 1,
                    'faculty_id' => 1,
                    'department_id' => 34
                ]),
                'state' => 'sent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Poziadanie o pracoviska',
                'user_id' => 1,
                'data' => json_encode([
                    'university_id' => 2,
                    'faculty_id' => 3,
                    'department_id' => 10
                ]),
                'state' => 'sent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Poziadanie o role Autor',
                'user_id' => 2,
                'data' => json_encode([
                    'conference_id' => 3,
                ]),
                'state' => 'sent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Poziadanie o role Recenzent',
                'user_id' => 2,
                'data' => json_encode([
                    'conference_id' => 4,
                ]),
                'state' => 'sent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
