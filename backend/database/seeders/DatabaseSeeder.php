<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $table = new User();
        $table->name = "test";
        $table->email = "test@test.test";
//        $table->password = "testtest";
        $table->password = '$2y$10$j6pSt9/zHzJ7x5zlrdCq5OHYHg1HMEZo2y4q0wDgMC33CjNHLmSTK';
        $table->save();
    }
}
