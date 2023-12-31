<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobHistoryController extends Controller
{
    public function addJobHistory(Request $request)
    {
        $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'locale' => ['required', 'string', 'in:en,ro,ja'],
        ]);
        $user = $request->user();
        $user->jobHistory()->create($request->all());
    }

    public function editJobHistory(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
            'company' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'locale' => ['required', 'string', 'in:en,ro,ja'],
        ]);
        $user = $request->user();
        $jobHistory = $user->jobHistory()->find($request->id);
        $jobHistory->update($request->all());
    }

    public function deleteJobHistory(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
            'locale' => ['required', 'string', 'in:en,ro,ja'],
        ]);
        $user = $request->user();
        $jobHistory = $user->jobHistory()->find($request->id);
        $jobHistory->delete();
    }
}
