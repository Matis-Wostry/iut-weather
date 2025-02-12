<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiKey;

    /**
     * WeatherService constructor.
     *
     * Initializes the API key from the Laravel configuration.
     */
    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    /**
     * Get the current weather for a specified city.
     *
     * This method fetches the real-time weather data from OpenWeather API
     * using the city name and returns it in JSON format.
     *
     * @param string $cityName The name of the city.
     * @return array|null The weather data as an associative array or null if the request fails.
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
     * Get a multi-day weather forecast for a specified city.
     *
     * This method retrieves a 5-day weather forecast from OpenWeather API,
     * processes the data to extract daily average temperatures and dominant weather conditions.
     *
     * @param string $cityName The name of the city.
     * @return array|null The processed forecast data as an associative array or null if the request fails.
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
     * Get hourly weather forecast data for a specific day in a given city.
     *
     * This method retrieves 5-day weather forecast data and filters it
     * to extract hourly details for a specific date.
     *
     * @param string $cityName The name of the city.
     * @param string $date The date for which hourly data is requested (YYYY-MM-DD format).
     * @return array|null The hourly forecast data as an associative array or null if the request fails.
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
