<?php

use App\Http\Controllers\Profile\EducationHistoryController;
use App\Http\Controllers\Profile\JobHistoryController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SkillController;
use App\Http\Controllers\Profile\UserFilesController;

// Profile routes

Route::middleware('auth')
    ->name('profile.')
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name(
            'show'
        );
        Route::patch('/profile', [ProfileController::class, 'update'])->name(
            'update'
        );
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
            'destroy'
        );
    });

// Avatar, user, socials, and description routes
Route::middleware('auth')
    ->name('profile.update.')
    ->prefix('profile/update')
    ->group(function () {
        Route::post('/avatar', [
            ProfileController::class,
            'updateAvatar',
        ])->name('avatar');
        Route::post('/user', [ProfileController::class, 'updateUser'])->name(
            'user'
        );
        Route::post('/socials', [
            ProfileController::class,
            'updateSocials',
        ])->name('socials');
        Route::post('/description', [
            ProfileController::class,
            'updateDescription',
        ])->name('description');
    });

// Job history routes
Route::middleware('auth')
    ->name('profile.update.')
    ->prefix('profile/update')
    ->group(function () {
        Route::post('/jobHistory', [
            JobHistoryController::class,
            'addJobHistory',
        ])->name('jobHistory');
        Route::put('/jobHistory', [
            JobHistoryController::class,
            'editJobHistory',
        ])->name('jobHistory');
        Route::delete('/jobHistory', [
            JobHistoryController::class,
            'deleteJobHistory',
        ])->name('jobHistory');
    });

// Education history routes
Route::middleware('auth')
    ->name('profile.update.')
    ->prefix('profile/update')
    ->group(function () {
        Route::post('/educationHistory', [
            EducationHistoryController::class,
            'addeducationHistory',
        ])->name('educationHistory');
        Route::put('/educationHistory', [
            EducationHistoryController::class,
            'editeducationHistory',
        ])->name('educationHistory');
        Route::delete('/educationHistory', [
            EducationHistoryController::class,
            'deleteeducationHistory',
        ])->name('educationHistory');
    });

// Skills routes
Route::middleware('auth')->group(function () {
    Route::post('/profile/update/skills', [
        SkillController::class,
        'editUserSkills',
    ])->name('profile.update.skills');
    Route::post('/get/skills', [SkillController::class, 'search'])->name(
        'get.skills'
    );
});

// User files routes
Route::middleware('auth')
    ->name('profile.update.')
    ->prefix('profile/update')
    ->group(function () {
        Route::post('/files', [UserFilesController::class, 'addFile'])->name(
            'files'
        );
        Route::delete('/files', [
            UserFilesController::class,
            'deleteFile',
        ])->name('files');
    });
