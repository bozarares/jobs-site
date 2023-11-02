<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Profile\SkillController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/try-recruiting', [CompanyController::class, 'create'])->name(
        'companies.create'
    );
    Route::post('/try-recruiting', [CompanyController::class, 'store'])->name(
        'companies.store'
    );
    Route::get('/my-companies', [CompanyController::class, 'index'])->name(
        'companies.index'
    );
    Route::middleware('can.edit.company')->group(function () {
        Route::patch('/company/{company}/update/description', [
            CompanyController::class,
            'updateDescription',
        ])->name('companies.update.description');
        Route::patch('/company/{company}/update/contact', [
            CompanyController::class,
            'updateContact',
        ])->name('companies.update.contact');
        Route::patch('/company/{company}/update/logo', [
            CompanyController::class,
            'updateLogo',
        ])->name('companies.update.logo');
        Route::post('/company/{company}/add-job', [
            JobController::class,
            'store',
        ])->name('jobs.store');
    });
});

Route::post('/job/{job}/application', [
    ApplicationController::class,
    'get',
])->name('job.get.application');
Route::patch('/job/{job}/application/{application}', [
    ApplicationController::class,
    'setStatus',
])->name('application.set.status');
Route::post('/job/{job}/application/load', [
    ApplicationController::class,
    'load',
])->name('job.load.application');

Route::get('/jobs/{job}/application/{application}', [
    ApplicationController::class,
    'show',
])->name('job.show.application');
Route::post('/job/{job}/update/skills', [
    SkillController::class,
    'editJobSkills',
])->name('job.update.skills');
Route::patch('/job/{job}/update/description', [
    JobController::class,
    'updateDescription',
])->name('job.update.description');
Route::post('/job/{job}/apply', [ApplicationController::class, 'apply'])->name(
    'job.apply'
);
Route::delete('/job/{job}', [JobController::class, 'delete'])->name(
    'job.delete'
);
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/company/{company}', [CompanyController::class, 'show'])->name(
    'companies.show'
);
