<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PrivacyController;

// Authentication
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('auth.showSignupForm');
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

// Restaurant Page
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

// Comments Section
Route::post('/restaurants/{restaurantId}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::patch('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Contact Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Report Page
Route::get('/reports/new', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::patch('/reports/{id}', [ReportController::class, 'update'])->name('reports.update');
Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');

// Privacy Policy Page
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy.index');
