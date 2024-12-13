<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiKey;

    public function __construct()
    {
        // Retrieve API key from config
        $this->apiKey = config('services.openweather.key');
    }

    /**
     * Get current weather for a given city.
     */
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

    /**
     * Get a multi-day forecast for a given city.
     */
    public function getForecastForCity($cityName)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $cityName,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $dailyData = [];
            foreach ($data['list'] as $forecast) {
                $date = \Carbon\Carbon::createFromTimestamp($forecast['dt'])->format('Y-m-d');

                if (!isset($dailyData[$date])) {
                    $dailyData[$date] = [
                        'temperatures' => [],
                        'weatherDescriptions' => []
                    ];
                }

                $dailyData[$date]['temperatures'][] = $forecast['main']['temp'];
                $dailyData[$date]['weatherDescriptions'][] = $forecast['weather'][0]['description'];
            }

            $result = [];
            foreach ($dailyData as $date => $info) {
                $averageTemp = round(array_sum($info['temperatures']) / count($info['temperatures']), 1);
                $weatherFrequency = array_count_values($info['weatherDescriptions']);
                arsort($weatherFrequency);
                $dominantWeather = array_key_first($weatherFrequency);

                $result[] = [
                    'date' => $date,
                    'averageTemp' => $averageTemp,
                    'dominantWeather' => $dominantWeather
                ];
            }

            return $result;
        }

        return null;
    }

    /**
     * Get hourly forecast data for a specific day for a given city.
     */
    public function getHourlyForecastForDay($cityName, $date)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $cityName,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $hourlyData = [];
            foreach ($data['list'] as $forecast) {
                $forecastDate = \Carbon\Carbon::createFromTimestamp($forecast['dt'])->format('Y-m-d');

                if ($forecastDate === $date) {
                    $hourlyData[] = [
                        'time' => \Carbon\Carbon::createFromTimestamp($forecast['dt'])->format('H:i'),
                        'temp' => $forecast['main']['temp'],
                        'weather' => $forecast['weather'][0]['description']
                    ];
                }
            }

            return $hourlyData;
        }

        return null;
    }
}
