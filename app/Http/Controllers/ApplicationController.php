<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\UserFile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    public function apply(Request $request, Job $job)
    {
        $request->validate([
            'files' => 'required|array',
            'files*' => 'required|exists:user_files,id',
        ]);

        $user = auth()->user();

        if (
            $job
                ->applications()
                ->where('user_id', $user->id)
                ->exists()
        ) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You have already applied to this job',
                ],
                400
            );
        }

        $files = UserFile::where('user_id', $user->id)
            ->whereIn('id', $request->input('files'))
            ->get();

        $application = $job->applications()->create(['user_id' => $user->id]);

        $application->files()->attach($files);
        return response()->json(
            ['success' => true, 'message' => 'Application successfully sent'],
            200
        );
    }

    public function getLocalizedData(Request $request, Job $job)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'locale' => 'required|string|in:en,ro,ja',
        ]);
        $application = Application::findOrFail(
            $request->input('application_id')
        );
        $localizedData = $application->user->getLocalizedDataAttribute(
            $request->input('locale')
        );
        return response()->json(['localizedData' => $localizedData]);
    }
    public function get(Request $request, Job $job)
    {
        $request_validated = $request->validate([
            'application_id' => 'nullable|exists:applications,id',
            'locale' => 'required|string|in:en,ro,ja',
        ]);

        $applicationId = $request_validated['application_id'] ?? null;

        if ($applicationId === null) {
            $currentApplication = $job
                ->applications()
                ->where('status', 'open')
                ->orWhere('status', 'accepted')
                ->first();
        }
        if ($applicationId) {
            $currentApplication = $job
                ->applications()
                ->where('id', $applicationId)
                ->first();
        }

        if (!$currentApplication) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No open applications found for this job',
                ],
                404
            );
        }

        /** @var \App\Models\Application $currentApplication */
        if ($currentApplication->seen_at === null) {
            $currentApplication->seen_at = now();
            $currentApplication->save();
        }

        $localizedData = $currentApplication->user->getLocalizedDataAttribute(
            $request->locale
        );
        $currentApplication->user->localizedData = $localizedData;

        $previousId = $job
            ->applications()
            ->where('status', $currentApplication->status)
            ->where('id', '<', $currentApplication->id)
            ->max('id');

        $nextId = $job
            ->applications()
            ->where('status', $currentApplication->status)
            ->where('id', '>', $currentApplication->id)
            ->min('id');

        return response()->json([
            'success' => true,
            'application' => $currentApplication,
            'previous' => $previousId,
            'next' => $nextId,
        ]);
    }

    public function show(Request $request, Job $job, Application $application)
    {
        if ($application->job_id !== $job->id) {
            abort(404);
        }

        $locale = $request->user->locale ?? 'en';

        $previousId = $job
            ->applications()
            ->where('status', $application->status)
            ->where('id', '<', $application->id)
            ->max('id');

        $nextId = $job
            ->applications()
            ->where('status', $application->status)
            ->where('id', '>', $application->id)
            ->min('id');

        if ($application->seen_at === null) {
            $application->seen_at = now();
            $application->save();
        }

        $localizedData = $application->user->getLocalizedDataAttribute($locale);
        $application->user->localizedData = $localizedData;

        return Inertia::render('Applications/Show', [
            'application' => $application,
            'previous' => $previousId,
            'next' => $nextId,
        ]);
    }

    public function navigate(Request $request)
    {
        $request_validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'application_id' => 'required|exists:applications,id',
            'direction' => 'required|string|in:next,previous',
        ]);

        $user = auth()->user();
        $job = Job::findOrFail($request_validated['job_id']);

        if ($job->company->owner !== $user->id) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>
                        'You are not authorized to navigate these applications',
                ],
                401
            );
        }

        $currentApplicationId = $request_validated['application_id'];
        $direction = $request_validated['direction'];

        $applicationQuery = Application::where('job_id', $job->id)
            ->where('status', 'open')
            ->orderBy('id', $direction === 'next' ? 'asc' : 'desc');

        $application =
            $direction === 'next'
                ? $applicationQuery
                    ->where('id', '>', $currentApplicationId)
                    ->first()
                : $applicationQuery
                    ->where('id', '<', $currentApplicationId)
                    ->latest('id')
                    ->first();

        if ($application) {
            return response()->json(
                [
                    'success' => true,
                    'application' => $application->load('files'),
                ],
                200
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => "No more applications in the {$direction} direction",
            ],
            404
        );
    }

    public function load(Request $request, Job $job)
    {
        $request_validated = $request->validate([
            'page' => 'required|integer',
            'status' => 'required|string|in:open,accepted',
        ]);

        $applications = $job
            ->applications()
            ->with('user')
            ->where('status', $request_validated['status'])
            ->paginate(20, ['*'], 'page', $request_validated['page']);

        $applicationsData = $applications
            ->getCollection()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'userName' => $application->user->name,
                ];
            });

        return response()->json([
            'applications' => $applicationsData,
            'lastPage' => $applications->lastPage(),
        ]);
    }

    public function setStatus(
        Request $request,
        Job $job,
        Application $application
    ) {
        $request_validated = $request->validate([
            'status' => 'required|string|in:open,accepted,closed,hired',
            'message' => 'nullable|string',
        ]);

        if (
            $application->status === 'accepted' &&
            $request_validated['status'] === 'accepted'
        ) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'This application is already accepted',
                ],
                400
            );
        }

        if (
            $application->status === 'open' &&
            $request_validated['status'] === 'hired'
        ) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>
                        'You cannot hire an applicant without accepting them first',
                ],
                400
            );
        }

        $application->status = $request_validated['status'];
        $application->message = $request_validated['message'] ?? null;
        $application->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Application status successfully changed',
            ],
            200
        );
    }
}
