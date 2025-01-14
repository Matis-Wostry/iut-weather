<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function currentWeather(Request $request)
    {
        $city = $request->query('place');

        if (!$city) {
            return response()->json(['error' => 'The query parameter "place" is required.'], 400);
        }

        $data = $this->weatherService->getWeatherForCity($city);

        if (!$data) {
            return response()->json(['error' => 'Weather data could not be retrieved for the specified city.'], 404);
        }

        return response()->json($data, 200);
    }

    public function forecast(Request $request)
    {
        $city = $request->query('place');

        if (!$city) {
            return response()->json(['error' => 'The query parameter "place" is required.'], 400);
        }

        $data = $this->weatherService->getForecastForCity($city);

        if (!$data) {
            return response()->json(['error' => 'Forecast data could not be retrieved for the specified city.'], 404);
        }

        return response()->json($data, 200);
    }
}

