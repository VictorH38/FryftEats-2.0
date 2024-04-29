<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\RestaurantResource;
use App\Http\Resources\FavoriteResource;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {
        $favorites = Favorite::where('user_id', $userId)->with('restaurant')->get();

        return RestaurantResource::collection($favorites->pluck('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($userId);
        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'restaurant_id' => $request->input('restaurant_id')
        ]);

        return response()->json($favorite, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($userId, $restaurantId)
    {
        $favorite = Favorite::where('user_id', $userId)->where('restaurant_id', $restaurantId)->first();

        if ($favorite) {
            return new FavoriteResource($favorite);
        }

        return response()->json(['message' => 'The restaurant is not in the user\'s favorites'], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $userId, $restaurantId)
    {
        $favorite = Favorite::where('user_id', $userId)
            ->where('restaurant_id', $restaurantId)
            ->first();
    
        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found'], 404);
        }
    
        $favorite->delete();
    
        return response()->json(['message' => 'Favorite removed successfully'], 204);
    }
}
