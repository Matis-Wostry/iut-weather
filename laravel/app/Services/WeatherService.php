<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getWeatherForCity($cityName)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $cityName,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getForecastForCity($cityName)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $cityName,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            return $response->json();
        }
    
        return null;

        return null;
    }
}