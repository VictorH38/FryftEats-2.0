<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully.', 'user' => $user]);
    }

    /**
     * Remove the authenticated user from storage and revoke all tokens.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->tokens->each->delete();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
