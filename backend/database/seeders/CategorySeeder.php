<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(
            ['name' => 'Category1'],
            ['description' => 'Lorem Ipsum description for Category 1']
        );

        Category::firstOrCreate(
            ['name' => 'Category2'],
            ['description' => 'Lorem Ipsum description for Category 2']
        );
    }
}
