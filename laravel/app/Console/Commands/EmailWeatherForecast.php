<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherService;
use App\Mail\WeatherForecastMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailWeatherForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:email-forecast {city : The name of the city to fetch weather forecast for} {--email= : The email address to send the forecast to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch weather forecast for a city and send it via email with a CSV attachment';

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
        // Retrieve the city name from arguments
        $cityName = $this->argument('city');

        // Retrieve the email option or use the default from .env
        $recipientEmail = $this->option('email') ?? config('mail.from.address');

        $this->info("Fetching weather forecast for {$cityName}...");

        // Call the WeatherService to get forecast data
        $forecastData = $this->weatherService->getForecastForCity($cityName);

        if (!$forecastData) {
            $this->error("Could not fetch weather forecast for {$cityName}. Please check the city name and try again.");
            return 1;
        }

        // Generate the HTML table from forecast data
        $htmlTable = $this->generateHtmlTable($forecastData, $cityName);

        // Generate the CSV file from forecast data
        $csvPath = $this->generateCsv($forecastData, $cityName);

        // Send the email with the HTML table and CSV attachment
        Mail::to($recipientEmail)->send(new WeatherForecastMail($cityName, $htmlTable, $csvPath));

        // Delete the CSV file after sending the email
        Storage::delete($csvPath);

        $this->info("Weather forecast for {$cityName} has been emailed to {$recipientEmail}.");

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

        // Create the directory if it doesn't exist
        Storage::makeDirectory('public/csv');

        // Open a stream to write the CSV
        $handle = fopen(storage_path("app/{$filepath}"), 'w');

        // Write the header row
        fputcsv($handle, ['Date', 'Average Temperature (°C)', 'Dominant Weather']);

        // Write the forecast data
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
