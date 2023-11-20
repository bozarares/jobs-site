<?php

use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

// All profile-related routes are protected with 'auth' middleware
Route::middleware('auth')->group(function () {
    Route::post('/language', [
        UserPreferenceController::class,
        'changeLanguage',
    ])->name('language');

    Route::post('/theme', [
        UserPreferenceController::class,
        'changeTheme',
    ])->name('changeTheme');

    Route::post('/profile/getLocalizedData', [
        UserPreferenceController::class,
        'getLocalizedData',
    ])->name('get.localized.data');
});
