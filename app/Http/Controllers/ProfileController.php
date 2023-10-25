<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Mews\Purifier\Facades\Purifier;

class ProfileController extends Controller
{
    public function show(): Response
    {
        $user = Auth::user();
        return Inertia::render('Profile/Show', [
            'user' => $user, //! We can use $user->only
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

    public function updateDescription(Request $request): RedirectResponse
    {
        $request_validated = $request->validate([
            'description' => ['nullable', 'string', 'max:2048'],
        ]);
        $user = $request->user();
        $request_validated['description'] = Purifier::clean(
            $request_validated['description']
        );
        $user->description = $request_validated['description'];

        $user->save();
        return Redirect::route('profile.show');
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
