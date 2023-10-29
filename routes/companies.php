<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
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

Route::get('/company/{company}', [CompanyController::class, 'show'])->name(
    'companies.show'
);
