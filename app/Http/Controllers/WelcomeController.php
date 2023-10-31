<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Foundation\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function show(Request $request)
    {
        $jobs = Job::with([
            'company' => function ($query) {
                $query
                    ->select('id', 'slug', 'name', 'logo', 'logo_extension')
                    ->without('jobs');
            },
        ])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Welcome', [
            'jobs' => $jobs,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
