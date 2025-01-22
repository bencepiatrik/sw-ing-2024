<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DepartmentUserSeeder extends Seeder
{
    public function run(): void
    {
        $departmentUsers = [
            [
                'user_id' => 2,
                'department_id' => 34,
            ],
            [
                'user_id' => 2,
                'department_id' => 35,
            ],
            [
                'user_id' => 3,
                'department_id' => 34,
            ],
            [
                'user_id' => 3,
                'department_id' => 35,
            ],
        ];

        foreach ($departmentUsers as $departmentUser) {
            // Skontrolujeme, či záznam už existuje
            if (!DB::table('department_user')
                ->where('user_id', $departmentUser['user_id'])
                ->where('department_id', $departmentUser['department_id'])
                ->exists()) {
                DB::table('department_user')->insert([
                    'user_id' => $departmentUser['user_id'],
                    'department_id' => $departmentUser['department_id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
