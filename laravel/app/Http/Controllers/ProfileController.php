<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Display the user's profile edit form.
     *
     * This method retrieves the authenticated user and returns the profile edit view.
     *
     * @param Request $request - The HTTP request containing user information
     * @return View - Returns the profile edit view
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * This method validates and updates the user's profile details.
     * If the email is changed, the email verification status is reset.
     *
     * @param ProfileUpdateRequest $request - The validated request containing updated profile data
     * @return RedirectResponse - Redirects back to the profile edit page with a status message
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account permanently.
     *
     * This method validates the user's password before proceeding with deletion.
     * It logs the user out, deletes their account, and invalidates the session.
     *
     * @param Request $request - The HTTP request containing the password confirmation
     * @return RedirectResponse - Redirects to the homepage after account deletion
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's email and forecast preferences.
     *
     * This method allows users to enable/disable email notifications and
     * set the scope of weather forecasts they wish to receive.
     *
     * @param Request $request - The HTTP request containing the new preferences
     * @return RedirectResponse - Redirects back with a status message
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        if ($request->has('wants_email')) {
            $user->wants_email = $request->input('wants_email') == '1';
        }

        if ($request->has('forecast_scope')) {
            $user->forecast_scope = $request->input('forecast_scope');
        }

        $user->save();

        return redirect()->back()->with('status', 'Preferences updated successfully!');
    }
}
