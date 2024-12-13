<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function home()
    {
        $favoriteCities = Auth::user()->favoriteCities;
        foreach ($favoriteCities as $city) {
            $city->weather = $this->weatherService->getWeatherForCity($city->name);
        }

        return view('home', compact('favoriteCities'));
    }

    public function searchWeather(Request $request)
    {
        $cityName = $request->input('city');
        $weatherData = $this->weatherService->getWeatherForCity($cityName);

        $favoriteCities = Auth::user()->favoriteCities;
        foreach ($favoriteCities as $city) {
            $city->weather = $this->weatherService->getWeatherForCity($city->name);
        }

        return view('home', compact('weatherData', 'favoriteCities'));
    }

    public function showForecast(Request $request)
    {
        $cityName = $request->input('city');
        $forecastData = $this->weatherService->getForecastForCity($cityName);

        return view('forecast', compact('forecastData', 'cityName'));
    }

    public function showDayDetails(Request $request)
    {
        $cityName = $request->input('city');
        $date = $request->input('date');

        $hourlyData = $this->weatherService->getHourlyForecastForDay($cityName, $date);

        return view('day-details', compact('hourlyData', 'cityName', 'date'));
    }
}
