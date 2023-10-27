<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationHistoryController extends Controller
{
    public function addEducationHistory(Request $request)
    {
        $request->validate([
            'institution' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);
        $user = $request->user();
        $user->educationHistory()->create($request->all());
    }

    public function editEducationHistory(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
            'institution' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);
        $user = $request->user();
        $educationHistory = $user->educationHistory()->find($request->id);
        $educationHistory->update($request->all());
    }

    public function deleteEducationHistory(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);
        $user = $request->user();
        $educationHistory = $user->educationHistory()->find($request->id);
        $educationHistory->delete();
    }
}
