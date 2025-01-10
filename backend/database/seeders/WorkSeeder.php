<?php

namespace Database\Seeders;

use App\Models\Work;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('name', 'Category1')->first();
        $user = User::where('email', 'autor@autor.autor')->first();

        Work::firstOrCreate(
            ['title' => 'Work Title 1'],
            [
                'category_id' => $category->id,
                'user_id' => $user->id,
                'description' => 'Work description 1',
                'status' => 1,
            ]
        );
    }
}
