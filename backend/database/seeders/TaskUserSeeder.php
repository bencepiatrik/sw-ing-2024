<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TaskUserSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'user_id' => 3,
                'role_id' => 3,
                'conference_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'role_id' => 3,
                'conference_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($tasks as $task) {
            // Skontrolujeme, či záznam už existuje
            if (!DB::table('task_user')
                ->where('user_id', $task['user_id'])
                ->where('role_id', $task['role_id'])
                ->where('conference_id', $task['conference_id'])
                ->exists()) {
                DB::table('task_user')->insert($task);
            }
        }
    }
}
