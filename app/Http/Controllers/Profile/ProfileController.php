<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserDescription;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Mews\Purifier\Facades\Purifier;

class ProfileController extends Controller
{
    public function applications(Request $request)
    {
        $user = Auth::user();
        $applications = $user
            ->applications()
            ->with([
                'job' => function ($query) {
                    $query->withTrashed()->with([
                        'company' => function ($query) {
                            $query
                                ->select(
                                    'id',
                                    'slug',
                                    'name',
                                    'logo',
                                    'logo_extension'
                                )
                                ->without('jobs');
                        },
                    ]);
                },
            ])
            ->get();
        return Inertia::render('Profile/Applications', [
            'applications' => $applications,
        ]);
    }

    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => ['required', 'string', 'in:en,ro,ja'],
        ]);
        $user = Auth::user();
        $user->locale = $request->language;
        Cookie::queue(
            Cookie::make(
                'user_locale',
                $request->language,
                60 * 24 * 30,
                '/',
                null,
                false,
                false
            )
        );
        $user->save();
    }

    public function changeTheme(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark'],
        ]);
        $user = Auth::user();
        $user->theme = $request->theme;
        Cookie::queue(
            Cookie::make(
                'theme',
                $request->theme,
                60 * 24 * 30,
                '/',
                null,
                false,
                false
            )
        );
        $user->save();
    }

    public function getLocalizedData(Request $request)
    {
        $request->validate([
            'locale' => ['required', 'string', 'in:en,ro,ja'],
        ]);

        $user = Auth::user();
        $localizedData = $user->getLocalizedDataAttribute($request->locale);
        return response()->json([
            'localizedData' => $localizedData,
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

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request_validated = $request->validate([
            'avatar' => ['required', 'string', 'max:255'],
            'extension' => [
                'required',
                'string',
                'in:png,jpg,jpeg,JPEG,PNG,JPG',
            ],
        ]);

        $user = $request->user();
        $filename =
            $request_validated['avatar'] .
            '.' .
            $request_validated['extension'];
        $filePath = storage_path('app/tmp/' . $filename);
        $targetDirectory = storage_path('app/public/users/avatars');

        if ($user->avatar) {
            $currentAvatarPath =
                $targetDirectory .
                '/' .
                $user->avatar .
                '.' .
                $user->avatar_extension;
            if (File::exists($currentAvatarPath)) {
                File::delete($currentAvatarPath);
            }
        }

        if (!File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }
        if (File::exists($filePath)) {
            File::move($filePath, $targetDirectory . '/' . $filename);
        }
        $user->avatar = $request_validated['avatar'];
        $user->avatar_extension = $request_validated['extension'];

        $user->save();

        return Redirect::route('profile.show');
    }

    public function updateUser(Request $request): RedirectResponse
    {
        $request_validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $user->tag = $request_validated['tag'];
        $user->name = $request_validated['name'];

        $user->save();

        return Redirect::route('profile.show');
    }

    public function updateSocials(Request $request): RedirectResponse
    {
        $request_validated = $request->validate([
            'phone_number' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_github' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
        ]);
        $user = $request->user();
        $user->phone_number = $request_validated['phone_number'];
        $user->social_linkedin = $request_validated['social_linkedin'];
        $user->social_github = $request_validated['social_github'];
        $user->social_facebook = $request_validated['social_facebook'];

        $user->save();
        return Redirect::route('profile.show');
    }

    public function updateDescription(Request $request)
    {
        $request_validated = $request->validate([
            'description' => ['nullable', 'string', 'max:2048'],
            'locale' => ['required', 'string', 'in:en,ro,ja'],
        ]);
        $user = $request->user();
        $request_validated['description'] = Purifier::clean(
            $request_validated['description']
        );
        UserDescription::updateOrCreate(
            [
                'user_id' => $user->id,
                'locale' => $request_validated['locale'],
            ],
            ['description' => $request_validated['description']]
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

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
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
