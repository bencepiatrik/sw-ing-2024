<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'neschvaleny pouzivatel']);
        Role::firstOrCreate(['name' => 'schvaleny pouzivatel']);
        Role::firstOrCreate(['name' => 'autor']);
        Role::firstOrCreate(['name' => 'recenzent']);
        Role::firstOrCreate(['name' => 'admin']);
    }
}
