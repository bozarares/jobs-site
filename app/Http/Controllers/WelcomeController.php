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

    public function search(Request $request)
    {
        $request_validated = $request->validate([
            'query' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $query = $request_validated['query'];
        $location = $request_validated['location'];

        $jobs = Job::with('company');

        if ($query) {
            $jobs = $jobs->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")->orWhere(
                    'description',
                    'LIKE',
                    "%{$query}%"
                );
            });
        }

        if ($location) {
            $jobs = $jobs->where('location', 'LIKE', "%{$location}%");
        }

        $jobs = $jobs->latest()->paginate(20);

        return JobMinimalResource::collection($jobs->items());
    }
}
