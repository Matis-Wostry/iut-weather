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
        // Récupérer le nom de la ville depuis la requête
        $cityName = $request->input('city');

        // Obtenir les prévisions sur 7 jours pour la ville
        $forecastData = $this->weatherService->getForecastForCity($cityName);

        // Afficher la vue avec les données de prévisions météo
        return view('forecast', compact('forecastData', 'cityName'));
    }
}
