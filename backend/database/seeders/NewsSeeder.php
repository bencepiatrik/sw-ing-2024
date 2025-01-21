<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
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
