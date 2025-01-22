<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            WorkSeeder::class,
            NewsSeeder::class,
            UniversitiesSeeder::class,
            FacultiesSeeder::class,
            DepartmentsSeeder::class,
            ConferenceSeeder::class,
            PublicationSeeder::class,
            NotificationSeeder::class,
            DepartmentUserSeeder::class,
            TaskUserSeeder::class,
        ]);
    }
}
