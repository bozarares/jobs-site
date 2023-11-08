<?php
use App\Http\Controllers\Profile\EducationHistoryController;
use App\Http\Controllers\Profile\JobHistoryController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SkillController;
use App\Http\Controllers\Profile\UserFilesController;
use Illuminate\Support\Facades\Route;

// All profile-related routes are protected with 'auth' middleware
Route::middleware('auth')->group(function () {
    // Basic profile actions
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'show')->name('show');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
            Route::get('/applications', 'applications')->name('applications');
        });

    // Update profile details
    Route::controller(ProfileController::class)
        ->prefix('profile/update')
        ->name('profile.update.')
        ->group(function () {
            Route::post('/avatar', 'updateAvatar')->name('avatar');
            Route::post('/user', 'updateUser')->name('user');
            Route::post('/socials', 'updateSocials')->name('socials');
            Route::post('/description', 'updateDescription')->name(
                'description'
            );
        });

    // Job history management
    Route::controller(JobHistoryController::class)
        ->prefix('profile/update/jobHistory')
        ->name('profile.update.jobHistory.')
        ->group(function () {
            Route::post('/', 'addJobHistory')->name('add');
            Route::put('/', 'editJobHistory')->name('edit');
            Route::delete('/', 'deleteJobHistory')->name('delete');
        });

    // Education history management
    Route::controller(EducationHistoryController::class)
        ->prefix('profile/update/educationHistory')
        ->name('profile.update.educationHistory.')
        ->group(function () {
            Route::post('/', 'addEducationHistory')->name('add');
            Route::put('/', 'editEducationHistory')->name('edit');
            Route::delete('/', 'deleteEducationHistory')->name('delete');
        });

    // Skill management
    Route::controller(SkillController::class)
        ->prefix('profile/update')
        ->name('profile.update.')
        ->group(function () {
            Route::post('/skills', 'editUserSkills')->name('skills');
        });
    Route::post('/get/skills', [SkillController::class, 'search'])->name(
        'get.skills'
    );

    // File management
    Route::controller(UserFilesController::class)
        ->prefix('profile/update/files')
        ->name('profile.update.files.')
        ->group(function () {
            Route::post('/', 'addFile')->name('add');
            Route::delete('/', 'deleteFile')->name('delete');
        });

    Route::post('/language', [
        ProfileController::class,
        'changeLanguage',
    ])->name('language');
    Route::post('/profile/getLocalizedData', [
        ProfileController::class,
        'getLocalizedData',
    ])->name('get.localized.data');
});
