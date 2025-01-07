<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use App\Models\News;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $reviewerRole = Role::firstOrCreate(['name' => 'reviewer']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create Categories
        $category = Category::firstOrCreate(
            ['name' => 'Category1'], 
            ['description' => 'Lorem Ipsum description for Category 1']
        );

        $category2 = Category::firstOrCreate(
            ['name' => 'Category2'], 
            ['description' => 'Lorem Ipsum description for Category 2']
        );

        // Create Users
        $user = User::firstOrCreate(
            ['email' => 'test@test.test'], 
            [
                'role_id' => $userRole->id,
                'name' => 'test',
                'password' => bcrypt('testtest'),
            ]
        );

        $reviewer = User::firstOrCreate(
            ['email' => 'review@review.review'], 
            [
                'role_id' => $reviewerRole->id,
                'name' => 'review',
                'password' => bcrypt('testtest'),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.admin'], 
            [
                'role_id' => $adminRole->id,
                'name' => 'admin',
                'password' => bcrypt('testtest'),
            ]
        );

        // Create Work
        Work::firstOrCreate(
            ['title' => 'Work Title 1'],
            [
                'category_id' => $category->id,
                'user_id' => $user->id,
                'description' => 'Work description 1',
                'status' => 1,
            ]
        );

        // Create News
        News::firstOrCreate(
            ['title' => 'Test News 1'], 
            [
                'text' => 'This is a test news item.',
                'image_source' => 'https://example.com/test-image.jpg',
                'link_source' => 'https://example.com',
            ]
        );

        News::firstOrCreate(
            ['title' => 'Test News 2'], 
            [
                'text' => 'This is a test news item.',
                'image_source' => 'https://example.com/test-image.jpg',
                'link_source' => 'https://example.com',
            ]
        );

        News::firstOrCreate(
            ['title' => 'Test News 3'], 
            [
                'text' => 'This is a test news item.',
                'image_source' => 'https://example.com/test-image.jpg',
                'link_source' => 'https://example.com',
            ]
        );
    }
}
