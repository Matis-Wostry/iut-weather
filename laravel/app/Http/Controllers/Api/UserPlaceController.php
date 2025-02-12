<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class UserPlaceController extends Controller
{
    public function index($userId)
    {
        $user = User::findOrFail($userId);
        $places = $user->favoriteCities()->paginate(10);

        return CityResource::collection($places);
    }

    public function store(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $data = $request->validate([
            'name' => 'required|string',
            'country' => 'nullable|string'
        ]);

        $city = City::firstOrCreate($data);
        $user->favoriteCities()->attach($city);

        return new CityResource($city);
    }


    public function destroy($userId, $place)
    {
        $user = User::findOrFail($userId);
        $city = City::findOrFail($place);

        $user->favoriteCities()->detach($city);

        return response()->json(['message' => 'City removed successfully.']);
    }

    public function toggleFavorite($userId, $place)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $city = City::find($place);
        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $pivotEntry = $user->favoriteCities()->where('city_id', $city->id)->first();

        if (!$pivotEntry) {
            return response()->json(['error' => 'City not found in user favorites'], 404);
        }

        $current = $pivotEntry->pivot->is_favorite;
        $user->favoriteCities()->updateExistingPivot($city->id, ['is_favorite' => !$current]);

        return response()->json([
            "id" => $city->id,
            "name" => $city->name,
            "favorite" => !$current
        ]);
    }

    public function toggleEmail($userId)
    {
        $user = User::findOrFail($userId);

        $user->wants_email = !$user->wants_email;
        $user->save();

        return response()->json([
            'message' => 'Email sending has been ' . ($user->wants_email ? 'enabled' : 'disabled'),
            'wants_email' => $user->wants_email
        ]);
    }

    public function updateForecastScope(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validatedData = $request->validate([
            'forecast_scope' => 'required|in:favorites,all,none'
        ]);

        $user->update([
            'forecast_scope' => $validatedData['forecast_scope']
        ]);

        return response()->json([
            'message' => 'Forecast scope updated successfully',
            'forecast_scope' => $user->forecast_scope
        ]);
    }

}

