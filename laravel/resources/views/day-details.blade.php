<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Details for {{ $date }}</title>
    <style>
        .details-container {
            text-align: center;
            margin: 20px;
        }
        .hourly-table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }
        .hourly-table th, .hourly-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .hourly-table th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <h1>Hourly Weather Details for {{ $cityName }} on {{ \Carbon\Carbon::parse($date)->format('l, F j') }}</h1>

    <div class="details-container">
        @if($hourlyData && count($hourlyData) > 0)
            <table class="hourly-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Temperature (°C)</th>
                        <th>Weather</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hourlyData as $hour)
                        <tr>
                            <td>{{ $hour['time'] }}</td>
                            <td>{{ $hour['temp'] }}°C</td>
                            <td>{{ ucfirst($hour['weather']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hourly data available for this day.</p>
        @endif
    </div>

    <a href="{{ route('weather.forecast', ['city' => $cityName]) }}">Back to Forecast</a>
</body>

</html>
