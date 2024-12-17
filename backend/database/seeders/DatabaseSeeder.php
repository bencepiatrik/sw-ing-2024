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
        $category->description = "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.";
        $category2 = new Category();
        $category2->name = "Category2";
        $category2->description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
        $category->save(); // Save the category first to generate an ID
        $category2->save();

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
