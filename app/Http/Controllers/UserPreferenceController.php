<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserPreferenceController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => ['required', 'string', 'in:en,ro,ja'],
        ]);
        $user = Auth::user();
        $preferences = $user->preferences()->firstOrCreate([]);
        $preferences->locale = $request->language;
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
        $preferences->save();
    }

    public function changeTheme(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark'],
        ]);
        $user = Auth::user();
        $preferences = $user->preferences()->firstOrCreate([]);
        $preferences->theme = $request->theme;
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
        $preferences->save();
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
}
