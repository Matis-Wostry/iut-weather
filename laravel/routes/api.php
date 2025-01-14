<?php

use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\UserPlaceController;
use Illuminate\Support\Facades\Route;


Route::get('/v2/weather', function () {
    return response()->json(['message' => 'Weather endpoint reached']);
});

Route::get('/test-api', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v1/weather', [WeatherController::class, 'currentWeather']);
    Route::get('/v1/weather/forecast', [WeatherController::class, 'forecast']);

    Route::get('/v1/users/places', [UserPlaceController::class, 'index']);
    Route::post('/v1/users/places', [UserPlaceController::class, 'store']);
    Route::delete('/v1/users/places/{place}', [UserPlaceController::class, 'destroy']);
    Route::patch('/v1/users/places/{place}/send-forecast', [UserPlaceController::class, 'toggleForecast']);
    Route::patch('/v1/users/places/{place}/favorite', [UserPlaceController::class, 'toggleFavorite']);
});
