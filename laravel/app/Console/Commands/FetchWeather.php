<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;

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
        // Récupérer le nom de la ville depuis les arguments
        $cityName = $this->argument('city');

        // Afficher un message de démarrage
        $this->info("Fetching weather data for {$cityName}...");

        // Appeler le service pour obtenir les données météo
        $weatherData = $this->weatherService->getWeatherForCity($cityName);

        // Vérifier si les données météo ont été récupérées avec succès
        if ($weatherData) {
            // Afficher les informations météo
            $this->info("City: {$weatherData['name']}");
            $this->info("Coordinates: Latitude {$weatherData['coord']['lat']}, Longitude {$weatherData['coord']['lon']}");
            $this->info("Temperature: {$weatherData['main']['temp']}°C");
            $this->info("Weather: " . ucfirst($weatherData['weather'][0]['description']));
            $this->info("Humidity: {$weatherData['main']['humidity']}%");
            $this->info("Wind Speed: {$weatherData['wind']['speed']} m/s");
        } else {
            // Afficher un message d'erreur si les données n'ont pas pu être récupérées
            $this->error("Could not fetch weather data for {$cityName}. Please check the city name and try again.");
        }

        return 0;
    }
}
