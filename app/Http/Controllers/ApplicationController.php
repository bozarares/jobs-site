<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\UserFile;
use Illuminate\Http\Request;

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
}
