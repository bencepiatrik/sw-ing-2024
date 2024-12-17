<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create Roles First
        $userRole = new Role();
        $userRole->name = "user";
        $userRole->save();

        $reviewerRole = new Role();
        $reviewerRole->name = "review";
        $reviewerRole->save();

        $adminRole = new Role();
        $adminRole->name = "admin";
        $adminRole->save();

        // Create Categories
        $category = new Category();
        $category->name = "Category1";
        $category->description = "Lorem Ipsum description for Category 1";
        $category->save();

        $category2 = new Category();
        $category2->name = "Category2";
        $category2->description = "Lorem Ipsum description for Category 2";
        $category2->save();

        // Create Users
        $user = new User();
        $user->role_id = $userRole->id; // Use the ID of the saved role
        $user->name = "test";
        $user->email = "test@test.test";
        $user->password = bcrypt("testtest"); // Hashing password
        $user->save();

        $reviewer = new User();
        $reviewer->role_id = $reviewerRole->id;
        $reviewer->name = "review";
        $reviewer->email = "review@review.review";
        $reviewer->password = bcrypt("testtest");
        $reviewer->save();

        $admin = new User();
        $admin->role_id = $adminRole->id;
        $admin->name = "admin";
        $admin->email = "admin@admin.admin";
        $admin->password = bcrypt("testtest");
        $admin->save();

        // Create Work
        $work = new Work();
        $work->title = "Work Title 1";
        $work->category_id = $category->id;
        $work->user_id = $user->id;
        $work->description = "Work description 1";
        $work->status = 1;
        $work->save();

        // Create News
        $news = new News();
        $news->title = "Test News 1";
        $news->text = "This is a test news item.";
        $news->image_source = "https://example.com/test-image.jpg";
        $news->link_source = "https://example.com";
        $news->save();
    }
}
