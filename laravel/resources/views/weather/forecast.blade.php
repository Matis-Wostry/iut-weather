<!-- resources/views/weather/forecast.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Weather Forecast for {{ $cityName }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-6 text-gray-900 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="forecast-container" style="display:flex; flex-wrap: wrap; justify-content: center;">
                @if($forecastData)
                    @foreach($forecastData as $day)
                        <div class="forecast-day" style="border: 1px solid #ccc; padding: 10px; margin: 10px; text-align: center; width: 150px; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);">
                            <h3>{{ \Carbon\Carbon::parse($day['date'])->format('l, F j') }}</h3>
                            <p>Average Temp: {{ $day['averageTemp'] }}°C</p>
                            <p class="mb-4">Weather: {{ ucfirst($day['dominantWeather']) }}</p>
                            <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 mt-4 hover:border-transparent rounded"  href="{{ route('forecast.day-details', ['city' => $cityName, 'date' => $day['date']]) }}">See Details</a>
                        </div>
                    @endforeach
                @else
                    <p>No forecast data available for this city.</p>
                @endif
            </div>

            <a href="{{ route('dashboard') }}" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"">Back to Dashboard</a>
        </div>
    </div>
</x-app-layout>
