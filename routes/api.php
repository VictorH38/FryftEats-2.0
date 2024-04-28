<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\FavoritesController;
// use App\Http\Controllers\Api\CommentController;
// use App\Http\Controllers\Api\ReportController;

// API routes for Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout']);

// API routes for User
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::patch('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
});

// API routes for Restaurants
Route::resource('restaurants', RestaurantController::class);

// API routes for Favorites
Route::get('/users/{userId}/favorites', [FavoritesController::class, 'index']);
Route::post('/users/{userId}/favorites', [FavoritesController::class, 'store']);
Route::delete('/users/{userId}/favorites/{restaurantId}', [FavoritesController::class, 'destroy']);

// API routes for Comments
// Route::post('/restaurants/{restaurantId}/comments', [CommentController::class, 'store']);
// Route::patch('/comments/{id}', [CommentController::class, 'update']);
// Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

// API routes for Reports
// Route::get('/reports', [ReportController::class, 'index']);
// Route::post('/reports', [ReportController::class, 'store']);
// Route::patch('/reports/{id}', [ReportController::class, 'update']);
// Route::delete('/reports/{id}', [ReportController::class, 'destroy']);
