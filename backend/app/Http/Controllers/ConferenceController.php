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
        if (!$id) {
            return response()->json(['error' => 'Department ID is required'], 400);
        }

        $conferences = Conference::where('department_id', $id)
            ->with('department')
            ->get();


        return response()->json($conferences);
    }

    public function store(Request $request)
    {
        // 1. Validácia vstupov
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'type' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
            'expiration_date' => 'required|date',
        ]);

        // 2. Vytvorenie konferencie
        $conference = Conference::create($validated);

        // 3. Vrátenie odpovede
        return response()->json([
            'message' => 'Konferencia bola úspešne vytvorená.',
            'conference' => $conference,
        ], 201);
    }

}
