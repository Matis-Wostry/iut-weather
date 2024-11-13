<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Auth;

class FavoriteCityService
{
    public function addFavorite($cityData)
    {
        $city = City::firstOrCreate($cityData);

        Auth::user()->favoriteCities()->syncWithoutDetaching([$city->id => ['is_favorite' => false]]);
    }

    public function removeFavorite($cityId)
    {
        Auth::user()->favoriteCities()->detach($cityId);
    }

    public function markFavorite($cityId){
        $user = Auth::user();

        $favoriteCities = $user->favoriteCities;

        foreach ($favoriteCities as $city) {
            $user->favoriteCities()->updateExistingPivot($city->id, ['is_favorite' => false]);
        }

        $user->favoriteCities()->updateExistingPivot($cityId, ['is_favorite' => true]);
    }

    public function unmarkFavorite($cityId){
        Auth::user()->favoriteCities()->updateExistingPivot($cityId, ['is_favorite' => false]);
    }
}
