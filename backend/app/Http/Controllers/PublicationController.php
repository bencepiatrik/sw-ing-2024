<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $conferenceId = $request->query('conference_id');

        if (!$conferenceId) {
            return response()->json(['error' => 'Conference ID is required'], 400);
        }

        $publications = Publication::where('conference_id', $conferenceId)->get();

        return response()->json($publications);
    }

    public function show($id): JsonResponse
    {
        $publication = Publication::find($id);

        if (!$publication) {
            return response()->json(['message' => 'Publication not found'], 404);
        }

        return response()->json($publication);
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'conference_id' => 'required|integer|exists:conferences,id',
        ]);

        // Create the publication
        $publication = Publication::create([
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'conference_id' => $request->conference_id,
            'user_id' => auth()->id(),
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'Publication created successfully.',
            'publication' => $publication,
        ], 201);
    }

}
