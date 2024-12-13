<!-- resources/views/weather/day-details.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Hourly Weather Details for {{ $cityName }} on {{ \Carbon\Carbon::parse($date)->format('l, F j') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-6 text-gray-900 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="details-container" style="text-align: center; margin: 20px;">
                @if($hourlyData && count($hourlyData) > 0)
                    <table class="hourly-table" style="margin:20px auto; border-collapse: collapse; width:80%;">
                        <thead>
                            <tr>
                                <th style="border:1px solid #ccc; padding:10px; text-align:center;">Time</th>
                                <th style="border:1px solid #ccc; padding:10px; text-align:center;">Temperature (°C)</th>
                                <th style="border:1px solid #ccc; padding:10px; text-align:center;">Weather</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hourlyData as $hour)
                                <tr>
                                    <td style="border:1px solid #ccc; padding:10px; text-align:center;">{{ $hour['time'] }}</td>
                                    <td style="border:1px solid #ccc; padding:10px; text-align:center;">{{ $hour['temp'] }}°C</td>
                                    <td style="border:1px solid #ccc; padding:10px; text-align:center;">{{ ucfirst($hour['weather']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No hourly data available for this day.</p>
                @endif
            </div>

                <a href="{{ route('weather.forecast', ['city' => $cityName]) }}" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Back to Forecast</a>
        </div>
    </div>
</x-app-layout>
