<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the authenticated user.
     */
    public function index(Request $request)
    {
        return $request->user();
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);
        return response()->json(['message' => 'User updated successfully.', 'user' => $user]);
    }

    /**
     * Remove the authenticated user from storage.
     */
    public function destroy()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }
}
