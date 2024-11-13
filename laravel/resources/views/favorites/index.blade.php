<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Cities Management</title>
</head>
<body>
    <h1>Manage Your Favorite Cities</h1>

    <!-- Display success messages if present -->
    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

        <!-- Form to add a new favorite city -->
    <h2>Add a New City</h2>

    <!-- Button to access the favorites page -->
    <a href="{{ route('home') }}">
        <button>Home</button>
    </a>

    <hr>
    
    <form action="{{ route('favorites.add') }}" method="POST">
        @csrf
        <!-- Input for city name -->
        <label for="name">City Name:</label>
        <input type="text" name="name" id="name" required>

        <!-- Input for country (optional) -->
        <label for="country">Country (optional):</label>
        <input type="text" name="country" id="country">

        <!-- Submit button to add the city -->
        <button type="submit">Add to List</button>
    </form>

    <hr>

        <!-- List of cities with options to update or remove -->
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

                    <!-- Form to mark or unmark as favorite -->
                    <form action="{{ route('favorites.toggle', $city->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit">
                            @if ($city->pivot->is_favorite)
                                Unmark Favorite
                            @else
                                Mark as Favorite
                            @endif
                        </button>
                    </form>

                    <!-- Form to remove the city from favorites -->
                    <form action="{{ route('favorites.remove', $city->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Remove</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>

