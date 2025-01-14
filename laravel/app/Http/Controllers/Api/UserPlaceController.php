<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class UserPlaceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $places = $user->favoriteCities()->paginate(10);

        return CityResource::collection($places);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string', 'country' => 'nullable|string']);
        $city = City::firstOrCreate($data);

        Auth::user()->favoriteCities()->attach($city);

        return new CityResource($city);
    }

    public function destroy($place)
    {
        $city = City::findOrFail($place);
        Auth::user()->favoriteCities()->detach($city);

        return response()->json(['message' => 'City removed successfully.']);
    }

    public function toggleForecast($place)
    {
        $city = City::findOrFail($place);
        $user = Auth::user();

        $current = $user->favoriteCities()->where('city_id', $city->id)->first()->pivot->receive_forecasts ?? false;
        $user->favoriteCities()->updateExistingPivot($city->id, ['receive_forecasts' => !$current]);

        return new CityResource($city);
    }

    public function toggleFavorite($place)
    {
        $city = City::findOrFail($place);
        $user = Auth::user();

        $current = $user->favoriteCities()->where('city_id', $city->id)->first()->pivot->is_favorite ?? false;
        $user->favoriteCities()->updateExistingPivot($city->id, ['is_favorite' => !$current]);

        return new CityResource($city);
    }
}

