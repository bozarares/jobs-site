<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobExperience;
use App\Models\JobLevel;
use App\Models\JobType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobController extends Controller
{
    public function show(Request $request, Job $job)
    {
        return Inertia::render('Jobs/Show', [
            'job' => $job->load(['company']),
        ]);
    }
    public function store(Request $request, Company $company)
    {
        $request_validated = $request = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'location' => 'required|string|max:255',
            'salary' => 'numeric|nullable',
            'level' => 'required|array',
            'level.*' => 'required|exists:job_levels,level',
            'type' => 'required|exists:job_types,type',
        ]);

        $level_ids = JobLevel::whereIn(
            'level',
            $request_validated['level']
        )->pluck('id');

        $job = new Job([
            'title' => $request_validated['title'],
            'description' => $request_validated['description'],
            'location' => $request_validated['location'],
            'salary' => $request_validated['salary'],
        ]);

        $jobType = JobType::where('type', $request_validated['type'])->first();
        $job->type()->associate($jobType);
        $company->jobs()->save($job);
        $job->jobLevels()->attach($level_ids);

        return response()->json(
            ['success' => true, 'message' => 'File deleted successfully'],
            200
        );
    }
}
