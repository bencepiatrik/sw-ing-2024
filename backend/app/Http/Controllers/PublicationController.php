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
}
