<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RestaurantController;
// use App\Http\Controllers\Api\FavoritesController;
// use App\Http\Controllers\Api\CommentController;
// use App\Http\Controllers\Api\ReportController;

// API routes for User
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API routes for Authentication
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);
// Route::post('/register', [AuthController::class, 'register']);

// API routes for Restaurants
Route::resource('restaurants', RestaurantController::class);

// API routes for Favorites
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/favorites', [FavoritesController::class, 'index']);
//     Route::post('/favorites', [FavoritesController::class, 'store']);
//     Route::delete('/favorites/{id}', [FavoritesController::class, 'destroy']);
// });

// API routes for Comments
// Route::post('/restaurants/{restaurantId}/comments', [CommentController::class, 'store']);
// Route::patch('/comments/{id}', [CommentController::class, 'update']);
// Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

// API routes for Reports
// Route::get('/reports', [ReportController::class, 'index']);
// Route::post('/reports', [ReportController::class, 'store']);
// Route::patch('/reports/{id}', [ReportController::class, 'update']);
// Route::delete('/reports/{id}', [ReportController::class, 'destroy']);
