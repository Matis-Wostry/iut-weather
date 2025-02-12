<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class UserPlaceController extends Controller
{

    /**
     * Retrieve the list of favorite cities of the authenticated user.
     * The response is paginated to limit the number of cities per request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        $places = $user->favoriteCities()->paginate(10);

        return CityResource::collection($places);
    }


    /**
     * Add or remove a city from the user's favorite places.
     * If the city already exists in the user's list, it is removed.
     * Otherwise, it is added to the list.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string',
            'country' => 'nullable|string'
        ]);

        $city = City::firstOrCreate($data);

        $exists = $user->favoriteCities()->where('city_id', $city->id)->exists();

        if ($exists) {
            $user->favoriteCities()->detach($city);
            return response()->json([
                'message' => 'City removed from user places.',
                'city' => new CityResource($city),
                'added' => false
            ]);
        } else {
            $user->favoriteCities()->attach($city);
            return response()->json([
                'message' => 'City added to user places.',
                'city' => new CityResource($city),
                'added' => true
            ]);
        }
    }

    /**
     * Toggle a city as favorite for the authenticated user.
     * If the city is already marked as favorite, it is removed.
     * Otherwise, it is set as the only favorite city for the user.
     *
     * @param int $place - ID of the city to toggle favorite status
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite($place)
    {
        $user = Auth::user();
        $city = City::findOrFail($place);

        $pivotEntry = $user->favoriteCities()->where('city_id', $city->id)->first();

        if (!$pivotEntry) {
            return response()->json(['error' => 'City not found in user list'], 404);
        }

        $current = $pivotEntry->pivot->is_favorite ?? false;

        if (!$current) {
            $user->favoriteCities()->updateExistingPivot(
                $user->favoriteCities()->pluck('cities.id')->toArray(),
                ['is_favorite' => false]
            );
        }


        $user->favoriteCities()->updateExistingPivot($city->id, ['is_favorite' => !$current]);

        return response()->json([
            'message' => 'Favorite status updated.',
            'city' => new CityResource($city),
            'favorite' => !$current
        ]);
    }

    /**
     * Toggle email notifications for the authenticated user.
     * This determines whether the user wants to receive weather forecast emails.
     *
     * @param int $place - (Not used, but required for route consistency)
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleForecast($place)
    {
        $user = Auth::user();

        $city = City::findOrFail($place);

        $user->wants_email = !$user->wants_email;
        $user->save();

        return response()->json([
            'message' => 'Email notifications have been ' . ($user->wants_email ? 'enabled' : 'disabled'),
            'wants_email' => $user->wants_email
        ]);
    }
}
