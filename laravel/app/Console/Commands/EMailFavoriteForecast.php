<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;
use App\Mail\WeatherForecastMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

// php artisan weather:send-weekly-emails

class EmailFavoriteForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:send-weekly-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly weather forecast emails to users who opted in';

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
        $this->info("Fetching users who opted for weekly weather emails...");

        $users = User::where('wants_email', true)->get();

        if ($users->isEmpty()) {
            $this->info("No users found with weekly email preference enabled.");
            return 0;
        }

        foreach ($users as $user) {
            $this->info("Processing user: {$user->email}");

            $favoriteCities = $user->favoriteCities;

            // Filtrer les villes selon la préférence
            if ($user->forecast_scope == 'favorite') {
                $favoriteCities = $favoriteCities->filter(function ($city) {
                    return $city->pivot->is_favorite; // Garde uniquement la ville favorite
                });
            }

            foreach ($favoriteCities as $city) {
                $this->info("Fetching forecast for city: {$city->name}");
                $forecastData = $this->weatherService->getForecastForCity($city->name);

                if (!$forecastData) {
                    $this->error("Failed to fetch forecast for city: {$city->name}");
                    continue;
                }

                $htmlTable = $this->generateHtmlTable($forecastData, $city->name);
                $csvPath = $this->generateCsv($forecastData, $city->name);

                Mail::to($user->email)->send(new WeatherForecastMail($city->name, $htmlTable, $csvPath));
                $this->info("Email sent to {$user->email} for city: {$city->name}");

                Storage::delete($csvPath);
            }
        }

        $this->info("Weekly weather emails sent successfully.");
        return 0;
    }

    /**
     * Generate an HTML table from forecast data.
     *
     * @param array $forecastData
     * @param string $cityName
     * @return string
     */
    protected function generateHtmlTable(array $forecastData, string $cityName): string
    {
        $html = "<h1>Weather Forecast for {$cityName}</h1>";
        $html .= "<table border='1' cellpadding='5' cellspacing='0'>";
        $html .= "<thead><tr><th>Date</th><th>Average Temperature (°C)</th><th>Dominant Weather</th></tr></thead>";
        $html .= "<tbody>";

        foreach ($forecastData as $day) {
            $formattedDate = \Carbon\Carbon::parse($day['date'])->format('l, F j');
            $averageTemp = $day['averageTemp'];
            $dominantWeather = ucfirst($day['dominantWeather']);

            $html .= "<tr>";
            $html .= "<td>{$formattedDate}</td>";
            $html .= "<td>{$averageTemp}°C</td>";
            $html .= "<td>{$dominantWeather}</td>";
            $html .= "</tr>";
        }

        $html .= "</tbody></table>";

        return $html;
    }

    /**
     * Generate a CSV file from forecast data.
     *
     * @param array $forecastData
     * @param string $cityName
     * @return string Path to the generated CSV file
     */
    protected function generateCsv(array $forecastData, string $cityName): string
    {
        $filename = "weather_forecast_{$cityName}_" . now()->format('Ymd_His') . ".csv";
        $filepath = "public/csv/{$filename}";

        Storage::makeDirectory('public/csv');

        $handle = fopen(storage_path("app/{$filepath}"), 'w');

        fputcsv($handle, ['Date', 'Average Temperature (°C)', 'Dominant Weather']);

        foreach ($forecastData as $day) {
            $formattedDate = \Carbon\Carbon::parse($day['date'])->format('l, F j');
            $averageTemp = $day['averageTemp'];
            $dominantWeather = ucfirst($day['dominantWeather']);

            fputcsv($handle, [$formattedDate, "{$averageTemp}°C", $dominantWeather]);
        }

        fclose($handle);

        return $filepath;
    }
}
