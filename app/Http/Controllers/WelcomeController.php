<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobMinimalResource;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function show()
    {
        $jobs = Job::with('company')
            ->when(Auth::check(), function ($query) {
                $query
                    ->with([
                        'applications' => function ($query) {
                            $query->where('user_id', Auth::id());
                        },
                    ])
                    ->with([
                        'likes' => function ($query) {
                            $query->where('user_id', Auth::id());
                        },
                    ]);
            })
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Welcome', [
            'jobs' => JobMinimalResource::collection($jobs),
        ]);
    }
}
