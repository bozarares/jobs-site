<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $jobs = Job::with([
                'company' => function ($query) {
                    $query
                        ->select('id', 'slug', 'name', 'logo', 'logo_extension')
                        ->without('jobs');
                },
            ])
                ->addSelect([
                    'application_date' => Application::select('created_at')
                        ->whereColumn('job_id', 'jobs.id')
                        ->where('user_id', $user->id)
                        ->limit(1),
                ])
                ->addSelect([
                    'seen_at' => Application::select('seen_at')
                        ->whereColumn('job_id', 'jobs.id')
                        ->where('user_id', $user->id)
                        ->limit(1),
                ])
                ->addSelect([
                    'status' => Application::select('status')
                        ->whereColumn('job_id', 'jobs.id')
                        ->where('user_id', $user->id)
                        ->limit(1),
                ])

                ->latest()
                ->limit(5)
                ->get();
        } else {
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
        }

        return Inertia::render('Welcome', [
            'jobs' => $jobs,
            // 'canLogin' => Route::has('login'),
            // 'canRegister' => Route::has('register'),
        ]);
    }
}
