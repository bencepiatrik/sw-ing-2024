<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $conference = Conference::all();
            return response()->json($conference);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show($id): JsonResponse
    {
        $conference = Conference::find($id);

        if (!$conference) {
            return response()->json(['message' => 'Conference not found'], 404);
        }

        return response()->json($conference);
    }

    public function findForUser($id)
    {
        // $id is the department_id
        $conferences = Conference::where('department_id', $id)->get();

        if ($conferences->isEmpty()) {
            return response()->json([
                'message' => 'No conferences found for the given department.'
            ], 404);
        }

        return response()->json($conferences);
    }


}
