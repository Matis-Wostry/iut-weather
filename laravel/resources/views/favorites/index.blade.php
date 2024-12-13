<!-- resources/views/favorites/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage Your Favorite Cities
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-6 text-gray-900 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            @if (session('success'))
                <div style="color: green;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form to add a new favorite city -->
            <h2><strong>Add a New City</strong></h2>
            <form action="{{ route('favorites.add') }}" method="POST">
                @csrf
                <label for="name">City Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="country">Country (optional):</label>
                <input type="text" name="country" id="country">
                <button type="submit">Add to List</button>
            </form>

            <!-- Form to update user's email preferences -->
            <form action="{{ route('update.preferences') }}" method="POST" style="margin-top:20px;">
                @csrf
                <label>
                    <input type="checkbox" name="wants_email" {{ Auth::user()->wants_email ? 'checked' : '' }}>
                    I want to receive daily weather emails
                </label>
                <button type="submit">Save Preferences</button>
            </form>

            <hr style="margin:20px 0;"/>

            <!-- List of user's cities -->
            <h2>Your Cities</h2>
            @if($favoriteCities->isEmpty())
                <p>You don't have any favorite cities yet.</p>
            @else
                <ul>
                    @foreach ($favoriteCities as $city)
                        <li>
                            {{ $city->name }} 
                            @if ($city->pivot->is_favorite)
                                <strong>(Favorite)</strong>
                            @endif

                            <form action="{{ route('favorites.toggle', $city->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button class="bg-transparent mb-4 hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">
                                    @if ($city->pivot->is_favorite)
                                        Unmark Favorite
                                    @else
                                        Mark as Favorite
                                    @endif
                                </button>
                            </form>

                            <form action="{{ route('favorites.remove', $city->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
