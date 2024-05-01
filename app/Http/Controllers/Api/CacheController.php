<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CacheController extends Controller
{
    /**
     * Retrieve the list of cached restaurants by a custom cache key.
     */
    public function index(Request $request)
    {
        $cacheKey = $request->input('cache_key', 'default_restaurants');
        $restaurants = Cache::get($cacheKey, []);
        return response()->json($restaurants);
    }

    /**
     * Store a list of restaurants in the cache using a custom cache key.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cache_key' => 'required|string',
            'data.*.id' => 'required|integer',
            'data.*.name' => 'required|string|max:255',
            'data.*.address' => 'required|string|max:255',
            'data.*.phone_number' => 'nullable|string',
            'data.*.cuisine' => 'nullable|string',
            'data.*.rating' => 'nullable|numeric',
            'data.*.price' => 'nullable|string',
            'data.*.url' => 'nullable|url',
            'data.*.image_url' => 'nullable|url',
            'data.*.latitude' => 'required|string',
            'data.*.longitude' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Cache::put($request->cache_key, $request->input('data'), 60 * 24 * 7);

        return response()->json(['message' => 'Restaurants cached successfully under key: ' . $request->cache_key]);
    }

    /**
     * Remove a cached entry using a given key.
     */
    public function destroy($cache_key)
    {
        if (Cache::has($cache_key)) {
            Cache::forget($cache_key);
            return response()->json(['message' => 'Cache successfully deleted for key: ' . $cache_key], 200);
        }

        return response()->json(['error' => 'Cache key not found'], 404);
    }
}
