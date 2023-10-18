<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/try-recruiting', [CompanyController::class, 'create'])->name(
    'companies.create'
);
Route::post('/try-recruiting', [CompanyController::class, 'store'])->name(
    'companies.store'
);
