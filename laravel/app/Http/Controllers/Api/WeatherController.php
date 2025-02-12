<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weatherService;

    /**
     * Inject the WeatherService dependency into the controller.
     *
     * @param WeatherService $weatherService - Service responsible for fetching weather data
     */
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Retrieve the current weather for a specified city.
     *
     * This method expects a query parameter `place` containing the name of the city.
     * If the city is not provided, a 400 error is returned.
     * If the weather data is unavailable, a 404 error is returned.
     *
     * @param Request $request - The HTTP request containing the query parameter
     * @return \Illuminate\Http\JsonResponse - The current weather data or an error message
     */
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

    /**
     * Retrieve the weather forecast for a specified city.
     *
     * This method expects a query parameter `place` containing the name of the city.
     * If the city is not provided, a 400 error is returned.
     * If the forecast data is unavailable, a 404 error is returned.
     *
     * @param Request $request - The HTTP request containing the query parameter
     * @return \Illuminate\Http\JsonResponse - The weather forecast data or an error message
     */
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

