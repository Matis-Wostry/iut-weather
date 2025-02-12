<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Auth;

class FavoriteCityService
{
    /**
     * Add a city to the user's list of favorite cities.
     *
     * If the city does not exist in the database, it will be created.
     * The city is added to the user's favorites but is not marked as a favorite initially.
     *
     * @param array $cityData The data of the city to be added (name, country).
     * @return void
     */
    public function addFavorite($cityData)
    {
        $city = City::firstOrCreate($cityData);

        Auth::user()->favoriteCities()->syncWithoutDetaching([$city->id => ['is_favorite' => false]]);
    }

    /**
     * Remove a city from the user's list of favorite cities.
     *
     * @param int $cityId The ID of the city to remove.
     * @return void
     */
    public function removeFavorite($cityId)
    {
        Auth::user()->favoriteCities()->detach($cityId);
    }

    /**
     * Mark a specific city as the user's favorite.
     *
     * This function unmarks all other favorite cities before setting the selected city as favorite.
     *
     * @param int $cityId The ID of the city to mark as favorite.
     * @return void
     */
    public function markFavorite($cityId){
        $user = Auth::user();

        $favoriteCities = $user->favoriteCities;

        foreach ($favoriteCities as $city) {
            $user->favoriteCities()->updateExistingPivot($city->id, ['is_favorite' => false]);
        }

        $user->favoriteCities()->updateExistingPivot($cityId, ['is_favorite' => true]);
    }

    /**
     * Unmark a city as the user's favorite.
     *
     * @param int $cityId The ID of the city to unmark.
     * @return void
     */
    public function unmarkFavorite($cityId){
        Auth::user()->favoriteCities()->updateExistingPivot($cityId, ['is_favorite' => false]);
    }
}
