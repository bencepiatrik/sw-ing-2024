<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
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
        $category = new Category();
        $category->name = "Category1";
        $category->save(); // Save the category first to generate an ID

        $user = new User();
        $user->name = "test";
        $user->email = "test@test.test";
        $user->password = '$2y$10$j6pSt9/zHzJ7x5zlrdCq5OHYHg1HMEZo2y4q0wDgMC33CjNHLmSTK'; //        $user->password = "testtest";
        $user->save();

        $work = new Work();
        $work->title = "Work Title 1";
        $work->category_id = $category->id;
        $work->user_id = $user->id;
        $work->description = "Work description 1";
        $work->status = 1;
        $work->save();

        $news = new News();
        $news->title = "Test News 1";
        $news->text = "This is a test news items.";
        $news->image_source = "https://example.com/test-image.jpg";
        $news->link_source = "https://example.com";
        $news->save();
    }
}
