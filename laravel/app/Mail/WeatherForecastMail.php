<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class WeatherForecastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cityName;
    public $htmlTable;
    public $csvPath;

    /**
     * Create a new message instance.
     *
     * @param string $cityName
     * @param string $htmlTable
     * @param string $csvPath
     */
    public function __construct(string $cityName, string $htmlTable, string $csvPath)
    {
        $this->cityName = $cityName;
        $this->htmlTable = $htmlTable;
        $this->csvPath = $csvPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Weather Forecast for {$this->cityName}")
            ->view('emails.weather_forecast')
            ->with([
                'cityName' => $this->cityName,
                'htmlTable' => $this->htmlTable,
            ])
            ->attach(storage_path("app/{$this->csvPath}"));
    }
}
