<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Profile\UserFilesController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('upload/process', [FileUploadController::class, 'process'])->name(
    'uploads.process'
);
Route::delete('upload/delete', [FileUploadController::class, 'destroy'])->name(
    'uploads.destroy'
);

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');
Route::post('/api/load', [WelcomeController::class, 'loadMoreJobs'])->name(
    'api.load.jobs'
);
Route::post('/api/search', [WelcomeController::class, 'search'])->name(
    'api.search.jobs'
);

Route::post('api/notifications', [
    App\Http\Controllers\NotificationController::class,
    'get',
])
    ->middleware(['auth'])
    ->name('api.notifications');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// TODO - Add middleware for viewing UserFiles (Owner and Employer)
Route::middleware('auth')->group(function () {
    Route::get('/users/files/{path}', [
        UserFilesController::class,
        'show',
    ])->name('users.files.show');
    Route::get('/users/files/{path}/download', [
        UserFilesController::class,
        'download',
    ])->name('users.files.download');
});

require __DIR__ . '/profile.php';
require __DIR__ . '/preferences.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/companies.php';
require __DIR__ . '/auth.php';
