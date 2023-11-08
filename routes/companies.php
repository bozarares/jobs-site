<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Profile\SkillController;
use Illuminate\Support\Facades\Route;

// Protected routes for authenticated users
Route::middleware('auth')->group(function () {
    // Company-related routes
    Route::controller(CompanyController::class)->group(function () {
        Route::get('/try-recruiting', 'create')->name('companies.create');
        Route::post('/try-recruiting', 'store')->name('companies.store');
        Route::get('/my-companies', 'index')->name('companies.index');
    });

    // Company and job management routes with additional 'can.edit.company' middleware
    Route::middleware('can.edit.company')->group(function () {
        Route::controller(CompanyController::class)->group(function () {
            Route::patch(
                '/company/{company}/update/description',
                'updateDescription'
            )->name('companies.update.description');
            Route::patch(
                '/company/{company}/update/contact',
                'updateContact'
            )->name('companies.update.contact');
            Route::patch('/company/{company}/update/logo', 'updateLogo')->name(
                'companies.update.logo'
            );
        });

        Route::controller(JobController::class)->group(function () {
            Route::post('/company/{company}/add-job', 'store')->name(
                'jobs.store'
            );
        });
    });

    // Job application management routes
    Route::controller(ApplicationController::class)->group(function () {
        Route::post('/job/{job}/application', 'get')->name(
            'job.get.application'
        );
        Route::patch('/job/{job}/application/{application}', 'setStatus')->name(
            'application.set.status'
        );
        Route::post('/job/{job}/application/load', 'load')->name(
            'job.load.application'
        );
        Route::post(
            '/job/{job}/application/getLocalizedData',
            'getLocalizedData'
        )->name('application.get.localized.data');
        Route::get('/jobs/{job}/application/{application}', 'show')->name(
            'job.show.application'
        );
    });

    // Job management routes
    // TODO - add 'can.edit.job' middleware
    Route::controller(JobController::class)->group(function () {
        Route::patch(
            '/job/{job}/update/description',
            'updateDescription'
        )->name('job.update.description');
        Route::delete('/job/{job}', 'delete')->name('job.delete');
    });
    Route::controller(SkillController::class)->group(function () {
        Route::post('/job/{job}/update/skills', 'editJobSkills')->name(
            'job.update.skills'
        );
    });

    // Application submission route
    Route::post('/job/{job}/apply', [
        ApplicationController::class,
        'apply',
    ])->name('job.apply');
});

// Publicly accessible routes
Route::controller(JobController::class)->group(function () {
    Route::get('/jobs/{job}', 'show')->name('jobs.show');
});

Route::get('/company/{company}', [CompanyController::class, 'show'])->name(
    'companies.show'
);
