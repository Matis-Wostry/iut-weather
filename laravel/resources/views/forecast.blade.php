<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast for {{ $cityName }}</title>
</head>
<body>
    <h1>Weather Forecast for {{ $cityName }}</h1>

    @if(isset($forecastData['list']))
        <ul>
            @foreach($forecastData['list'] as $day)
                <li>
                    {{ \Carbon\Carbon::createFromTimestamp($day['dt'])->format('l, F j') }}:
                    {{ $day['main']['temp'] }}°C, {{ $day['weather'][0]['description'] }}
                </li>
            @endforeach
        </ul>
    @else
        <p>No forecast data available for this city.</p>
    @endif

    <a href="{{ route('home') }}">Back to Home</a>
</body>
</html>
