<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    protected $weatherService;

    /**
     * WeatherController constructor.
     *
     * @param WeatherService $weatherService The service responsible for fetching weather data.
     */
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display the dashboard with a weather search form 
     * and the user's favorite cities' weather information.
     *
     * This function retrieves the authenticated user's favorite cities 
     * and fetches the weather data for each one. It also retrieves 
     * any previously searched weather data stored in the session.
     *
     * @param Request $request The incoming request.
     * @return \Illuminate\View\View The view displaying the dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $favoriteCities = $user ? $user->favoriteCities()->get() : collect();
        $weatherData = $request->session()->get('weatherData', null);

        foreach ($favoriteCities as $city) {
            $cityWeather = $this->weatherService->getWeatherForCity($city->name);
            $city->weather = $cityWeather;
        }

        return view('dashboard', compact('weatherData', 'favoriteCities'));
    }

    /**
     * Handle the weather search request and store the results in the session.
     *
     * This function processes a user's weather search by retrieving 
     * the weather data for a specified city and storing the results 
     * in the session to be displayed on the dashboard.
     *
     * @param Request $request The request containing the city name.
     * @return \Illuminate\Http\RedirectResponse Redirects to the dashboard with weather data.
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
     *
     * This function retrieves a city's weather forecast data 
     * and passes it to the forecast view.
     *
     * @param Request $request The request containing the city name.
     * @return \Illuminate\View\View The view displaying the weather forecast.
     */
    public function showForecast(Request $request)
    {
        $cityName = $request->query('city');
        $forecastData = $this->weatherService->getForecastForCity($cityName);

        return view('weather.forecast', compact('cityName', 'forecastData'));
    }

    /**
     * Display detailed hourly weather data for a given day and city.
     *
     * This function retrieves hourly weather data for a specific 
     * day and city and passes it to the day details view.
     *
     * @param Request $request The request containing the city name and date.
     * @return \Illuminate\View\View The view displaying the hourly weather data.
     */
    public function showDayDetails(Request $request)
    {
        $cityName = $request->query('city');
        $date = $request->query('date');
        $hourlyData = $this->weatherService->getHourlyForecastForDay($cityName, $date);

        return view('weather.day-details', compact('cityName', 'date', 'hourlyData'));
    }
}
