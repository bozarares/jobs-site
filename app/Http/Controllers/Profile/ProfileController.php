<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UpdateUserDescriptionRequest;
use App\Http\Requests\User\UpdateUserInfoRequest;
use App\Http\Requests\User\UpdateUserSocialsRequest;
use App\Http\Resources\ApplicationIndexResource;
use App\Models\UserDescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function applications(Request $request)
    {
        $user = Auth::user();
        $applications = $user
            ->applications()
            ->with([
                'job' => function ($query) {
                    $query->withTrashed()->with(['company']);
                },
            ])
            ->get();

        return Inertia::render('Profile/Applications', [
            'applications' => ApplicationIndexResource::collection(
                $applications
            ),
        ]);
    }

    public function show(): Response
    {
        $user = Auth::user();
        $locale = $user->locale;
        $localizedData = $user->getLocalizedDataAttribute($locale);
        return Inertia::render('Profile/Show', [
            'user' => $user,
            'localizedData' => $localizedData,
        ]);
    }

    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated())->save();
        return Redirect::route('profile.show');
    }

    public function updateUser(UpdateUserInfoRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated())->save();
        return Redirect::route('profile.show');
    }

    public function updateSocials(
        UpdateUserSocialsRequest $request
    ): RedirectResponse {
        $user = $request->user();
        $user->fill($request->validated())->save();

        return Redirect::route('profile.show');
    }

    public function updateDescription(UpdateUserDescriptionRequest $request)
    {
        $user = $request->user();

        UserDescription::updateOrCreate(
            [
                'user_id' => $user->id,
                'locale' => $request['locale'],
            ],
            ['description' => $request['description']]
        );
    }

    public function editEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
        ]);

        $user = $request->user();
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
