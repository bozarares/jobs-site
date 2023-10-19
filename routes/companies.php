<?php

use App\Http\Controllers\CompanyController;
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
    Route::get('/company/{company}', [CompanyController::class, 'show'])->name(
        'companies.show'
    );
});
