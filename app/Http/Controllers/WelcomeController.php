<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobMinimalResource;
use App\Models\Job;
use Illuminate\Http\Request;
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
            ->paginate(20);

        $jobsData = $jobs->items();

        return Inertia::render('Welcome', [
            'jobs' => JobMinimalResource::collection($jobsData),
            'lastPage' => $jobs->lastPage(),
        ]);
    }

    public function loadMoreJobs(Request $request)
    {
        $page = $request->input('page', 1);

        $jobs = Job::with('company')
            ->latest()
            ->paginate(20, ['*'], 'page', $page);

        return JobMinimalResource::collection($jobs->items());
    }
}
