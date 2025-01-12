<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\University;
use Illuminate\Http\Request;
use App\Notifications\WorkplaceSetNotification;
use Illuminate\Support\Facades\Auth;

class WorkplaceController extends Controller
{
    public function submitWorkplace(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'data' => 'required|array',
        ]);

        $notification = \DB::table('notifications')->insert([
            'type' => $validated['type'],
            'user_id' => Auth::id(),
            'data' => json_encode($validated['data']),
            'state' => 'sent',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Notification created successfully.'], 201);
    }

}
