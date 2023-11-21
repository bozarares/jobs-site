<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $request_validated = $request->validate([
            'job_slug' => 'required|exists:jobs,slug',
            'like_status' => 'required|in:-1,0,1',
        ]);
        $user = $request->user();
        $job = Job::where('slug', $request_validated['job_slug'])->first();
        $like = Like::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();
        if ($like) {
            if ($request_validated['like_status'] == 0) {
                $like->delete();
                return;
            }
            $like->like_status = $request_validated['like_status'];
            $like->save();
        } else {
            $like = Like::create([
                'user_id' => $user->id,
                'job_id' => $job->id,
                'like_status' => $request_validated['like_status'],
            ]);
        }
    }
}
