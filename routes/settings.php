<?php

use App\Http\Controllers\Profile\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'show'])->name(
        'settings.show'
    );
});
