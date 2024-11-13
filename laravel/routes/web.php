<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\FavoriteCityController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/favorites', [FavoriteCityController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteCityController::class, 'addFavorite'])->name('favorites.add');
    Route::delete('/favorites/{city}', [FavoriteCityController::class, 'removeFavorite'])->name('favorites.remove');
    Route::patch('/favorites/{city}/toggle', [FavoriteCityController::class, 'toggleFavorite'])->name('favorites.toggle');

});

Route::get('/home', [WeatherController::class, 'home'])->name('home');
Route::post('/weather/search', [WeatherController::class, 'searchWeather'])->name('weather.search');
Route::post('/weather/forecast', [WeatherController::class, 'showForecast'])->name('weather.forecast');

require __DIR__.'/auth.php';
