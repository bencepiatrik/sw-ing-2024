<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show($department_id): JsonResponse
    {
        // Hľadanie všetkých konferencií podľa department_id
        $conferences = Conference::where('department_id', $department_id)
        ->with('department') // Načítaj aj priradené oddelenie
        ->get();

        // Ak žiadne konferencie nenájdené
        if ($conferences->isEmpty()) {
            return response()->json(['message' => 'No conferences found for this department'], 404);
        }

        // Vrátenie konferencií ako JSON
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

    public function update(Request $request, $id)
    {
        $conference = Conference::find($id);

        if (!$conference) {
            return response()->json(['error' => 'Conference not found'], 404);
        }

        $conference->update($request->all());

        return response()->json(['message' => 'Conference updated successfully']);
    }

    public function checkUserTasks(Request $request, $conferenceId)
    {
        $user = auth()->user();
        $task = DB::table('task_user')
            ->join('roles', 'task_user.role_id', '=', 'roles.id')
            ->where('task_user.user_id', $user->id)
            ->where('task_user.conference_id', $conferenceId)
            ->select('roles.name as role_name')
            ->first();

        return response()->json(['role' => $task ? $task->role_name : null]);
    }

}
