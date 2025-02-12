<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FavoriteCityService;

class FavoriteCityController extends Controller
{
    protected $favoriteCityService;

    /**
     * Inject the FavoriteCityService dependency into the controller.
     *
     * @param FavoriteCityService $favoriteCityService - Service responsible for managing favorite cities
     */
    public function __construct(FavoriteCityService $favoriteCityService)
    {
        $this->favoriteCityService = $favoriteCityService;
    }

    /**
     * Add a city to the user's favorite list.
     *
     * This method receives city data (name and country) from the request,
     * passes it to the service, and redirects back with a success message.
     *
     * @param Request $request - The HTTP request containing city details
     * @return \Illuminate\Http\RedirectResponse - Redirects back with a success message
     */
    public function addFavorite(Request $request)
    {
        $this->favoriteCityService->addFavorite($request->only('name', 'country'));

        return redirect()->back()->with('success', 'City added to favorites');
    }

    /**
     * Remove a city from the user's favorite list.
     *
     * This method receives the city ID, passes it to the service,
     * and redirects back with a success message.
     *
     * @param int $cityId - The ID of the city to be removed
     * @return \Illuminate\Http\RedirectResponse - Redirects back with a success message
     */
    public function removeFavorite($cityId)
    {
        $this->favoriteCityService->removeFavorite($cityId);

        return redirect()->back()->with('success', 'City removed from favorites');
    }

    /**
     * Toggle the favorite status of a city for the authenticated user.
     *
     * If the city is currently marked as favorite, it will be unmarked.
     * Otherwise, it will be set as the user's favorite.
     *
     * @param int $cityId - The ID of the city to toggle favorite status
     * @return \Illuminate\Http\RedirectResponse - Redirects back with a success message
     */
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

    /**
     * Display the list of favorite cities for the authenticated user.
     *
     * Retrieves the user's favorite cities and passes them to the view.
     *
     * @return \Illuminate\View\View - The favorites index view with the list of cities
     */
    public function index(){
        $favoriteCities = auth()->user()->favoriteCities;
        return view('favorites.index', compact('favoriteCities'));
    }
}
