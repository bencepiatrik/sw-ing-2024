<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            [
                'type' => 'Poziadanie o pracoviska',
                'user_id' => 1,
                'data' => json_encode([
                    'university_id' => 1,
                    'faculty_id' => 1,
                    'department_id' => 34
                ]),
                'state' => 'sent',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'Poziadanie o role Autor',
                'user_id' => 2,
                'data' => json_encode([
                    'conference_id' => 3,
                ]),
                'state' => 'sent',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'Poziadanie o role Recenzent',
                'user_id' => 2,
                'data' => json_encode([
                    'conference_id' => 4,
                ]),
                'state' => 'sent',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($notifications as $notification) {
            if (!DB::table('notifications')
                ->where('type', $notification['type'])
                ->where('user_id', $notification['user_id'])
                ->where('data', $notification['data'])
                ->exists()) {
                DB::table('notifications')->insert($notification);
            }
        }
    }
}
