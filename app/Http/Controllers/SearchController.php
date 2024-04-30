<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $restaurants = collect();

        if ($request->has('restaurant')) {
            $term = $request->input('restaurant');
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

            $cacheKey = 'yelp-search-' . md5(http_build_query($queryParams));
            $cacheTime = 604800;

            $data = Cache::remember($cacheKey, $cacheTime, function () use ($queryParams) {
                $apiKey = env('YELP_API_KEY');
                $apiUrl = 'https://api.yelp.com/v3/businesses/search';

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept' => 'application/json'
                ])->get($apiUrl, $queryParams);

                return $response->successful() ? $response->json()['businesses'] : [];
            });

            foreach ($data as $item) {
                $restaurant = Restaurant::updateOrCreate(
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
                );

                $restaurants->push($restaurant);
            }

            $perPage = 12;
            $currentPage = $request->input('page', 1);
            $currentItems = $restaurants->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $paginatedItems = new LengthAwarePaginator($currentItems, $restaurants->count(), $perPage, $currentPage, [
                'path' => $request->url(),
                'query' => $request->query()
            ]);

            $paginatedItems->appends(['restaurant' => $term, 'price' => $price, 'sort_by' => $sortBy]);
        }

        return view('search.index', [
            'user' => $user,
            'results' => $paginatedItems ?? null,
        ]);
    }

    public function toggleFavorite(Request $request, $restaurantId)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $isFavorite = $request->input('isFavorite', false);
        $restaurant = Restaurant::find($restaurantId);

        if ($isFavorite) {
            $user->favorites()->detach($restaurantId);
            return response()->json([
                'success' => true, 
                'message' => $restaurant->name . ' was successfully removed from your favorites'
            ]);
        } else {
            $user->favorites()->attach($restaurantId, ['created_at' => now()]);
            return response()->json([
                'success' => true, 
                'message' => $restaurant->name . ' was successfully added to your favorites'
            ]);
        }
    }
}
