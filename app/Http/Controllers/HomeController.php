<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $user = User::with('favorites')->find($userId);
            
            return view('home.index', [
                'user' => $user,
                'favorites' => $user->favorites,
            ]);
        } else {
            return view('home.index', [
                'favorites' => collect(),
            ]);
        }
    }

    public function removeFromFavorites($restaurantId)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $user->favorites()->detach($restaurantId);
    }
}
