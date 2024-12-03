<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run()
    {
        News::create([
            'title' => 'Test News',
            'text' => 'This is a test news item.',
            'image_source' => 'https://example.com/test-image.jpg',
            'link_source' => 'https://example.com',
        ]);
    }
}
