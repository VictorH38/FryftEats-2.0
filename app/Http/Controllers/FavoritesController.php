<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;

class FavoritesController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $user = User::with(['favorites' => function($query) {
                $query->orderBy('pivot_created_at', 'desc')->get();
            }])->find($userId);

            return view('favorites.index', [
                'user' => $user,
                'favorites' => $user->favorites,
            ]);
        } else {
            return view('favorites.index', [
                'favorites' => collect(),
            ]);
        }
    }

    public function removeFromFavorites($restaurantId)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $restaurant = Restaurant::find($restaurantId);

        $user->favorites()->detach($restaurantId);
        return response()->json([
            'success' => true, 
            'message' => $restaurant->name . ' was successfully removed from your favorites'
        ]);
    }
}
