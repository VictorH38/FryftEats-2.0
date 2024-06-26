<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth/login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home.index');
        }
    
        return redirect()->route('auth.login')->with(['loginError' => 'The provided credentials do not match our records'])->withInput($request->except('password'));
    }

    public function showSignupForm() {
        return view('auth/signup');
    }

    public function signup(Request $request)
    {
        $messages = [
            'username.unique:users' => 'That username is already taken',
            'email.email' => 'That email is not a valid email address',
            'email.unique:users' => 'That email is already taken',
            'password.confirmed' => 'Those passwords do not match'
        ];
    
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], $messages);
    
        $user = User::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
    
        Auth::login($user);
        return redirect()->route('home.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home.index');
    }
}
