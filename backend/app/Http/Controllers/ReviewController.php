<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Publication;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'publicationId' => 'required|exists:publications,id',
            'reviewData' => 'required|array',
            'status' => 'required|in:prijatá,odmietnutá',
        ]);

        $publication = Publication::findOrFail($request->publicationId);

        // Create the review
        $review = Review::create([
            'publication_id' => $request->publicationId,
            'author_id' => $publication->user_id,
            'reviewer_id' => Auth::id(),
            'time' => Carbon::now(),
            'review_data' => $request->reviewData
        ]);

        $publication->status = $request->status;
        $publication->save();

        return response()->json([
            'message' => 'Review created successfully.',
            'review' => $review,
            'publication' => $publication,
        ], 201);
    }

    public function show($publicationId)
    {
        $review = Review::with(['author', 'reviewer'])->where('publication_id', $publicationId)->firstOrFail();

        if (is_string($review->review_data)) {
            $review->review_data = json_decode($review->review_data); // Dekódovanie JSON
        } else {
            $review->review_data;
        }
        return response()->json($review);
    }


}
