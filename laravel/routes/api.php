<?php

use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\UserPlaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // Weather API routes
    Route::get('/v1/weather', [WeatherController::class, 'currentWeather']);
    Route::get('/v1/weather/forecast', [WeatherController::class, 'forecast']);

    //User Places API routes
    Route::get('/v1/users/places', [UserPlaceController::class, 'index']);
    Route::post('/v1/users/places', [UserPlaceController::class, 'store']);
    Route::patch('/v1/users/places/{place}/send-forecast', [UserPlaceController::class, 'toggleForecast']);
    Route::patch('/v1/users/places/{place}/favorite', [UserPlaceController::class, 'toggleFavorite']);
});
