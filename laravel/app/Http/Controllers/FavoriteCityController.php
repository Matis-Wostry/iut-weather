<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FavoriteCityService;

class FavoriteCityController extends Controller
{
    protected $favoriteCityService;

    public function __construct(FavoriteCityService $favoriteCityService)
    {
        $this->favoriteCityService = $favoriteCityService;
    }

    public function addFavorite(Request $request)
    {
        // Call the service to add the city to the user's favorites
        $this->favoriteCityService->addFavorite($request->only('name', 'country'));

        return redirect()->back()->with('success', 'City added to favorites');
    }

    public function removeFavorite($cityId)
    {
        $this->favoriteCityService->removeFavorite($cityId);

        return redirect()->back()->with('success', 'City removed from favorites');
    }

    public function toggleFavorite($cityId)
    {
        $user = auth()->user();

        $favoriteCity = $user->favoriteCities()->where('city_id', $cityId)->first();

        if ($favoriteCity && $favoriteCity->pivot->is_favorite) {
            $this->favoriteCityService->unmarkFavorite($cityId);
            return redirect()->back()->with('success', 'Ville dé-favorisée');
        } else {
            $this->favoriteCityService->markFavorite($cityId);
            return redirect()->back()->with('success', 'Ville marquée comme favorite');
        }
    }

    public function index(){
        $favoriteCities = auth()->user()->favoriteCities;
        return view('favorites.index', compact('favoriteCities'));
    }
}
