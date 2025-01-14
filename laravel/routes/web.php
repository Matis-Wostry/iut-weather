<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\FavoriteCityController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Now the dashboard route calls the dashboard method in WeatherController
Route::get('/dashboard', [WeatherController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorite city routes
    Route::get('/favorites', [FavoriteCityController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteCityController::class, 'addFavorite'])->name('favorites.add');
    Route::delete('/favorites/{city}', [FavoriteCityController::class, 'removeFavorite'])->name('favorites.remove');
    Route::patch('/favorites/{city}/toggle', [FavoriteCityController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::post('/update-preferences', [ProfileController::class, 'updatePreferences'])->name('update.preferences');
});

// Weather routes
Route::post('/weather/search', [WeatherController::class, 'searchWeather'])->name('weather.search');
Route::get('/weather/forecast', [WeatherController::class, 'showForecast'])->name('weather.forecast');
Route::get('/weather/forecast/day-details', [WeatherController::class, 'showDayDetails'])->name('forecast.day-details');

Route::get('/test-csrf', function () {
    return response()->json(['message' => 'CSRF middleware works!']);
});

require __DIR__.'/auth.php';
