<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $unAuthRole = Role::where('name', 'neschvaleny pouzivatel')->first();
        $authRole = Role::where('name', 'schvaleny pouzivatel')->first();
        $authorRole = Role::where('name', 'autor')->first();
        $reviewerRole = Role::where('name', 'recenzent')->first();
        $adminRole = Role::where('name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'unauth@unauth.unauth'],
            [
                'role_id' => $unAuthRole->id,
                'name' => 'unauth',
                'password' => bcrypt('testtest'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'auth@auth.auth'],
            [
                'role_id' => $authRole->id,
                'name' => 'auth',
                'password' => bcrypt('testtest'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'autor@autor.autor'],
            [
                'role_id' => $authRole->id,
                'name' => 'autor',
                'password' => bcrypt('testtest'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'review@review.review'],
            [
                'role_id' => $authRole->id,
                'name' => 'review',
                'password' => bcrypt('testtest'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@admin.admin'],
            [
                'role_id' => $adminRole->id,
                'name' => 'admin',
                'password' => bcrypt('testtest'),
            ]
        );
    }
}
