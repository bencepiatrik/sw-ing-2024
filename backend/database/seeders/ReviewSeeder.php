<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use Illuminate\Support\Carbon;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        Review::create([
            'publication_id' => 1,
            'author_id' => 3,
            'reviewer_id' => 4,
            'time' => Carbon::now(),
            'review_data' => json_encode([
                "aktualnost" => "A",
                "zorientovanie" => "A",
                "vhodnostMetod" => "A",
                "rozsahUroven" => "A",
                "analyzaInterpretacia" => "A",
                "prehladnostLogika" => "A",
                "formalnaJazykova" => "A",
                "missingTitle" => false,
                "missingAuthorName" => false,
                "missingEmail" => false,
                "missingAbstract" => false,
                "invalidAbstractLength" => false,
                "missingKeywords" => false,
                "missingSections" => false,
                "missingReferences" => true,
                "missingTextReferences" => true,
                "missingImageReferences" => true,
                "missingImageDescriptions" => true,
                "strongPoints" => "asd",
                "weakPoints" => "asd",
            ]),
        ]);
    }
}
