<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function changeRole($id, Request $request) {
        try {
            // Validate the request input
            $request->validate([
                'role' => 'required|string|in:user,reviewer,admin',
            ]);

            // Fetch the role ID based on the role name
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return response()->json(['error' => 'Invalid role'], 400);
            }

            // Update the user's role_id
            $user = User::findOrFail($id);
            $user->role_id = $role->id;
            $user->save();

            return response()->json(['message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Change Role Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function deleteUser($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

}
