<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('department_user')->insert([
            'user_id' => 2,
            'department_id' => 34,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('department_user')->insert([
            'user_id' => 2,
            'department_id' => 35,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
