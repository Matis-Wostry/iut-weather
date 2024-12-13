<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
</head>
<body>
    <h1>Weather Dashboard</h1>

    <!-- Form to search for the weather of a specific city -->
    <form action="{{ route('weather.search') }}" method="POST">
        @csrf
        <label for="city">Search Weather for a City:</label>
        <input type="text" name="city" id="city" required>
        <button type="submit">Search</button>
    </form>

    <hr>

    <!-- Display the weather of the searched city -->
    @if(isset($weatherData))
        <h1>{{ $weatherData['name'] }}</h1>
        <h2>Coordinates: Latitude {{ $weatherData['coord']['lat'] }}, Longitude {{ $weatherData['coord']['lon'] }}</h2>
        <p>Current Temperature: {{ $weatherData['main']['temp'] }}°C</p>
        <p>Weather: {{ $weatherData['weather'][0]['description'] }}</p>

        <form action="{{ route('weather.forecast') }}" method="GET">
            @csrf
            <input type="hidden" name="city" value="{{ $weatherData['name'] }}">
            <button type="submit">View Forecast</button>
        </form>
    @endif

    <hr>

    <!-- Display the weather of favorite cities -->
    <h2>Your Favorite Cities Weather</h2>
    <!-- Button to access the favorites page -->
    <a href="{{ route('favorites.index') }}">
        <button >Manage Favorites</button>
    </a>
    @if($favoriteCities->isEmpty())
        <p>No favorite cities selected yet.</p>
    @else
        @foreach($favoriteCities as $city)
            <!-- Only display cities that are marked as favorite -->
            @if($city->pivot->is_favorite)
                <h3>{{ $city->name }} <strong>(Favorite)</strong></h3>
                @if(isset($city->weather))
                    <p>Current Temperature: {{ $city->weather['main']['temp'] }}°C</p>
                    <p>Weather: {{ $city->weather['weather'][0]['description'] }}</p>
                @else
                    <p>No weather data available for this city.</p>
                @endif
                <form action="{{ route('weather.forecast') }}" method="GET">
                    @csrf
                    <input type="hidden" name="city" value="{{ $city->name }}">
                    <button type="submit" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">View Forecast</button>
                </form>
            @endif
            
        @endforeach
    @endif
</body>
</html>
