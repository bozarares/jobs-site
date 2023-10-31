<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Profile\UserFilesController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('upload/process', [FileUploadController::class, 'process'])->name(
    'uploads.process'
);
Route::delete('upload/delete', [FileUploadController::class, 'destroy'])->name(
    'uploads.destroy'
);

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
require __DIR__ . '/companies.php';
require __DIR__ . '/auth.php';
