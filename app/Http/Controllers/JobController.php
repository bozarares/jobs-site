<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobExperience;
use App\Models\JobLevel;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class JobController extends Controller
{
    public function show(Request $request, Job $job)
    {
        $user = Auth::user();
        $already_applied = false;
        if ($user) {
            $already_applied = $user->alreadyApplied($job);
        }
        if ($user && $job->company->owner === $user->id) {
            $job->load(['applications']);
        }
        return Inertia::render('Jobs/Show', [
            'job' => $job->load(['company']),
            'applied' => $already_applied,
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

    public function updateDescription(Request $request, Job $job)
    {
        $request_validated = $request->validate([
            'description' => 'nullable|string|max:2048',
        ]);
        $request_validated['description'] = Purifier::clean(
            $request_validated['description']
        );
        $job->description = $request_validated['description'];
        $job->save();
    }

    public function delete(Request $request, Job $job)
    {
        $job->delete();
        return redirect()->route('welcome');
    }
}
