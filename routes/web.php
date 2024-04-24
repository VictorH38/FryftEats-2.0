<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ContactController;

// Authentication
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Search Page
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::post('/toggleFavorite/{restaurantId}', [SearchController::class, 'toggleFavorite'])->name('search.toggleFavorite')->middleware('auth');

// Favorties Page
Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
Route::post('/removeFromFavorites/{restaurantId}', [FavoritesController::class, 'removeFromFavorites'])->name('favorites.removeFromFavorites')->middleware('auth');

// Contact Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
