<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobExperience;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $request_validated = $request = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|nullable',
            'experience' => 'required|array',
            'experience.*' => 'required|exists:job_experiences,name',
            'type' => 'required|exists:job_types,type',
        ]);

        $experience_ids = JobExperience::whereIn(
            'name',
            $request_validated['experience']
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
        $job->jobExperiences()->attach($experience_ids);

        return response()->json(
            ['success' => true, 'message' => 'File deleted successfully'],
            200
        );
    }
}
