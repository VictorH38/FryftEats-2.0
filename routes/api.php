<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CacheController;

// API routes for Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// API routes for Users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::patch('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
});

// API routes for Restaurants
Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/paginate', [RestaurantController::class, 'paginate']);
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);
Route::post('/restaurants', [RestaurantController::class, 'store']);
Route::patch('/restaurants/{restaurant}', [RestaurantController::class, 'update']);
Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']);

// API routes for Favorites
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{userId}/favorites', [FavoritesController::class, 'index']);
    Route::get('/users/{userId}/favorites/{restaurantId}', [FavoritesController::class, 'show']);
    Route::post('/users/{userId}/favorites', [FavoritesController::class, 'store']);
    Route::delete('/users/{userId}/favorites/{restaurantId}', [FavoritesController::class, 'destroy']);
});

// API routes for Comments
Route::get('/restaurants/{restaurantId}/comments', [CommentController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/restaurants/{restaurantId}/comments', [CommentController::class, 'store']);
    Route::patch('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});

// API routes for Reports
Route::post('/reports', [ReportController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports/authenticated', [ReportController::class, 'storeAuthenticated']);
    Route::patch('/reports/{id}', [ReportController::class, 'update']);
    Route::delete('/reports/{id}', [ReportController::class, 'destroy']);
});

// API routes for Cache
Route::get('/cache/{cache_key}', [CacheController::class, 'index']);
Route::post('/cache', [CacheController::class, 'store']);
Route::delete('/cache/{cache_key}', [CacheController::class, 'destroy']);
