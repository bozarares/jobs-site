<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function show(): Response
    {
        $user = Auth::user();
        return Inertia::render('Settings/Show', [
            'user' => $user,
        ]);
    }
}
