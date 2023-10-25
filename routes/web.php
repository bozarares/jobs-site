<?php

use App\Http\Controllers\EducationHistoryController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\JobHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserFilesController;
use Illuminate\Foundation\Application;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name(
        'profile.show'
    );
    Route::post('/profile/update/avatar', [
        ProfileController::class,
        'updateAvatar',
    ])->name('profile.update.avatar');
    Route::post('/profile/update/user', [
        ProfileController::class,
        'updateUser',
    ])->name('profile.update.user');
    Route::post('/profile/update/socials', [
        ProfileController::class,
        'updateSocials',
    ])->name('profile.update.socials');
    Route::post('/profile/update/description', [
        ProfileController::class,
        'updateDescription',
    ])->name('profile.update.description');

    Route::post('/profile/update/jobHistory', [
        JobHistoryController::class,
        'addJobHistory',
    ])->name('profile.update.jobHistory');
    Route::put('/profile/update/jobHistory', [
        JobHistoryController::class,
        'editJobHistory',
    ])->name('profile.update.jobHistory');
    Route::delete('/profile/update/jobHistory', [
        JobHistoryController::class,
        'deleteJobHistory',
    ])->name('profile.update.jobHistory');

    Route::post('/profile/update/educationHistory', [
        EducationHistoryController::class,
        'addeducationHistory',
    ])->name('profile.update.educationHistory');
    Route::put('/profile/update/educationHistory', [
        EducationHistoryController::class,
        'editeducationHistory',
    ])->name('profile.update.educationHistory');
    Route::delete('/profile/update/educationHistory', [
        EducationHistoryController::class,
        'deleteeducationHistory',
    ])->name('profile.update.educationHistory');

    Route::post('/profile/update/skills', [
        SkillController::class,
        'editSkills',
    ])->name('profile.update.skills');

    Route::post('/profile/update/files', [
        UserFilesController::class,
        'addFile',
    ])->name('profile.update.files');

    Route::delete('/profile/update/files', [
        UserFilesController::class,
        'deleteFile',
    ])->name('profile.update.files');
    Route::post('/get/skills', [SkillController::class, 'search'])->name(
        'get.skills'
    );

    Route::patch('/profile', [ProfileController::class, 'update'])->name(
        'profile.update'
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
        'profile.destroy'
    );
});

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

require __DIR__ . '/companies.php';
require __DIR__ . '/auth.php';
