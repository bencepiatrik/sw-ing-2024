<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function update(Request $request)
    {
        // Získanie aktuálneho prihláseného používateľa
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Validácia vstupov
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
    
        // Aktualizácia údajov používateľa
        $user->update($validatedData);
    
        // Vrátenie odpovede
        return response()->json($user);
    }

}
