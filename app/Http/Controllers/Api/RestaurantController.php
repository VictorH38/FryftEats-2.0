<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Http\Resources\RestaurantResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurants = Restaurant::paginate();

        return RestaurantResource::collection($restaurants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $restaurant = null;
        if ($request->has('url')) {
            $restaurant = Restaurant::where('url', $request->input('url'))->first();
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'cuisine' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price' => 'nullable|string|max:255',
            'url' => ['nullable', 'url', 'max:255', Rule::unique('restaurants')->ignore($restaurant)],
            'image_url' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
            ], 422);
        }

        $restaurant = Restaurant::updateOrCreate(
            ['url' => $request->input('url')],
            [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone_number' => $request->input('phone_number'),
                'cuisine' => $request->input('cuisine'),
                'rating' => $request->input('rating'),
                'price' => $request->input('price'),
                'image_url' => $request->input('image_url'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ]
        );

        return response()->json($restaurant, $restaurant->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        return new RestaurantResource($restaurant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'cuisine' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'image_url' => 'nullable|url|max:255'
        ]);
    
        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
            ], 422);
        }
    
        $restaurant->update($request->all());

        return response()->json($restaurant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        try {
            $restaurant->favoredBy()->detach();
            $restaurant->comments()->delete();
            $restaurant->reports()->delete();
            $restaurant->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Restaurant successfully deleted along with all related data.'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the restaurant. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
