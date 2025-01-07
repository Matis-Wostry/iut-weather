<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;

/* php artisan weather:fetch Paris */

class FetchWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:fetch {city : The name of the city to fetch weather for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the current weather for a specified city';

    /**
     * The WeatherService instance.
     *
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * Create a new command instance.
     *
     * @param WeatherService $weatherService
     * @return void
     */
    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();

        $this->weatherService = $weatherService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cityName = $this->argument('city');

        $this->info("Fetching weather data for {$cityName}...");

        $weatherData = $this->weatherService->getWeatherForCity($cityName);

        if ($weatherData) {
            $this->info("City: {$weatherData['name']}");
            $this->info("Coordinates: Latitude {$weatherData['coord']['lat']}, Longitude {$weatherData['coord']['lon']}");
            $this->info("Temperature: {$weatherData['main']['temp']}°C");
            $this->info("Weather: " . ucfirst($weatherData['weather'][0]['description']));
            $this->info("Humidity: {$weatherData['main']['humidity']}%");
            $this->info("Wind Speed: {$weatherData['wind']['speed']} m/s");
        } else {
            $this->error("Could not fetch weather data for {$cityName}. Please check the city name and try again.");
        }

        return 0;
    }
}
