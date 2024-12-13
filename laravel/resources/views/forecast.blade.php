<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast for {{ $cityName }}</title>
    <style>
        .forecast-day {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            display: inline-block;
            text-align: center;
            width: 150px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .forecast-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
</head>

<body>
    <h1>Weather Forecast for {{ $cityName }}</h1>

    <div class="forecast-container">
        @if($forecastData)
            @foreach($forecastData as $day)
                <div class="forecast-day">
                    <h3>{{ \Carbon\Carbon::parse($day['date'])->format('l, F j') }}</h3>
                    <p>Average Temp: {{ $day['averageTemp'] }}°C</p>
                    <p>Weather: {{ ucfirst($day['dominantWeather']) }}</p>
                    <a href="{{ route('forecast.day-details', ['city' => $cityName, 'date' => $day['date']]) }}">See Details</a>
                </div>
            @endforeach
        @else
            <p>No forecast data available for this city.</p>
        @endif
    </div>

    <a href="{{ route('home') }}">Back to Home</a>
</body>

</html>
