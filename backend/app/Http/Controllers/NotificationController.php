<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Notification;
use App\Models\Role;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;

                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'state' => $notification->state,
                    'created_at' => $notification->created_at,
                    'user_email' => $notification->user->email ?? 'Unknown',
                    'university' => isset($data['university_id']) ? University::find($data['university_id'])->name ?? 'Unknown' : 'N/A',
                    'faculty' => isset($data['faculty_id']) ? Faculty::find($data['faculty_id'])->name ?? 'Unknown' : 'N/A',
                    'department' => isset($data['department_id']) ? Department::find($data['department_id'])->name ?? 'Unknown' : 'N/A',
                ];
            });

        return response()->json($notifications);
    }





    public function updateStateOld(Request $request, $id)
    {
        $request->validate([
            'state' => 'required|in:accepted,declined',
        ]);

        $notification = Notification::findOrFail($id);
        $notification->state = $request->state;
        $notification->save();

        return response()->json(['message' => 'Notification updated successfully.', 'notification' => $notification], 200);
    }

    public function updateState(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        $notification->state = $request->state;
        $notification->save();

        if ($notification->type === 'Poziadanie o pracoviska' && $notification->user->role->name === 'neschvaleny pouzivatel') {
            $approvedRole = Role::where('name', 'schvaleny pouzivatel')->first();

            if ($approvedRole) {
                $notification->user->role_id = $approvedRole->id;
                $notification->user->save();
            }

            $data = $notification->data;
            DB::table('department_user')->updateOrInsert(
                [
                    'user_id' => $notification->user->id,
                    'department_id' => $data['department_id'],
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Notification updated successfully.']);
    }

}
