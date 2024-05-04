<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Handles restaurant searches by checking the cache first, then querying Yelp if necessary.
     */
    public function index(Request $request)
    {
        $term = $request->input('term');
        $price = $request->input('price', '0');
        $sortBy = $request->input('sort_by', 'best_match');
        
        $queryParams = [
            'latitude' => 34.0259,
            'longitude' => -118.2853,
            'term' => $term,
            'radius' => 1500,
            'sort_by' => $sortBy,
            'limit' => 50
        ];

        if ($price == '0') {
            $queryParams['price'] = ['1', '2', '3', '4'];
        } else {
            $queryParams['price'] = $price;
        }
        
        $cacheKey = 'yelp-search-' . md5(json_encode($queryParams));
        $cacheTime = now()->addWeek();

        $restaurants = Cache::remember($cacheKey, $cacheTime, function () use ($queryParams) {
            $apiKey = env('YELP_API_KEY');
            $apiUrl = 'https://api.yelp.com/v3/businesses/search';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json'
            ])->get($apiUrl, $queryParams);

            if ($response->successful()) {
                $results = $response->json()['businesses'];
                return array_map(function ($item) {
                    return Restaurant::updateOrCreate(
                        ['url' => $item['url']],
                        [
                            'name' => $item['name'],
                            'address' => implode(", ", $item['location']['display_address']),
                            'phone_number' => $item['display_phone'],
                            'cuisine' => collect($item['categories'])->pluck('title')->implode(', '),
                            'rating' => $item['rating'],
                            'price' => $item['price'] ?? null,
                            'image_url' => $item['image_url'],
                            'latitude' => $item['coordinates']['latitude'],
                            'longitude' => $item['coordinates']['longitude']
                        ]
                    )->toArray();
                }, $results);
            } else {
                response()->json([
                    'url' => $apiUrl,
                    'params' => $queryParams,
                    'response' => $response->body()
                ]);
                return [];
            }

            return [];
        });

        return response()->json($restaurants);
    }
}
