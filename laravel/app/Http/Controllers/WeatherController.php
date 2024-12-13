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

    /**
     * Display the dashboard page with weather search form 
     * and user's favorite cities weather.
     * Here we assume that previously what was on "home" is now on "dashboard".
     */
    public function dashboard(Request $request)
{
    $user = Auth::user();
    $favoriteCities = $user ? $user->favoriteCities()->get() : collect();
    $weatherData = $request->session()->get('weatherData', null);

    // Fetch weather for each favorite city
    foreach ($favoriteCities as $city) {
        $cityWeather = $this->weatherService->getWeatherForCity($city->name);
        $city->weather = $cityWeather; // Attach the weather data to the city object
    }

    return view('dashboard', compact('weatherData', 'favoriteCities'));
}

    /**
     * Handle the weather search request and store results in session.
     */
    public function searchWeather(Request $request)
    {
        $cityName = $request->input('city');
        $weatherData = $this->weatherService->getWeatherForCity($cityName);

        // Store the data in session so we can display it on the dashboard
        $request->session()->put('weatherData', $weatherData);

        return redirect()->route('dashboard');
    }

    /**
     * Display the weather forecast for a given city.
     */
    public function showForecast(Request $request)
    {
        $cityName = $request->query('city');
        $forecastData = $this->weatherService->getForecastForCity($cityName);

        return view('weather.forecast', compact('cityName', 'forecastData'));
    }

    /**
     * Display detailed hourly weather data for a given day and city.
     */
    public function showDayDetails(Request $request)
    {
        $cityName = $request->query('city');
        $date = $request->query('date');
        $hourlyData = $this->weatherService->getHourlyForecastForDay($cityName, $date);

        return view('weather.day-details', compact('cityName', 'date', 'hourlyData'));
    }
}
