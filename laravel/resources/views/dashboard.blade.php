<!-- resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <!-- Weather search form -->
                <form action="{{ route('weather.search') }}" method="POST">
                    @csrf
                    <label for="city">Search Weather for a City:</label>
                    <input type="text" name="city" id="city" style="color: black;" required>
                    <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">Search</button>
                </form>

                <hr style="margin:20px 0;"/>

                <!-- Display searched city weather if available -->
                @if(isset($weatherData))
                    <h1>{{ $weatherData['name'] }}</h1>
                    <h2>Coordinates: Latitude {{ $weatherData['coord']['lat'] }}, Longitude {{ $weatherData['coord']['lon'] }}</h2>
                    <p>Current Temperature: {{ $weatherData['main']['temp'] }}°C</p>
                    <p>Weather: {{ $weatherData['weather'][0]['description'] }}</p>

                    <form action="{{ route('weather.forecast') }}" method="GET">
                        @csrf
                        <input type="hidden" name="city" value="{{ $weatherData['name'] }}">
                        <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">View Forecast</button>
                    </form>
                @endif

                <hr style="margin:20px 0;"/>

                <!-- Display user's favorite cities weather -->
                <h2><strong>Your Favorite Cities Weather</strong></h2>
                @if($favoriteCities->isEmpty())
                    <p>No favorite cities selected yet.</p>
                @else
                    @foreach($favoriteCities as $city)
                        @if($city->pivot->is_favorite)
                            <h3>{{ $city->name }}</h3>
                            @if(isset($city->weather))
                                <p>Coordinates: Latitude {{ $city->weather['coord']['lat'] }}, Longitude {{ $city->weather['coord']['lon'] }}</p>
                                <p>Current Temperature: {{ $city->weather['main']['temp'] }}°C</p>
                                <p>Weather: {{ $city->weather['weather'][0]['description'] }}</p>
                            @else
                                <p>No weather data available for this city.</p>
                            @endif

                            <form action="{{ route('weather.forecast') }}" method="GET">
                                @csrf
                                <input type="hidden" name="city" value="{{ $city->name }}">
                                <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">View Forecast</button>
                            </form>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
