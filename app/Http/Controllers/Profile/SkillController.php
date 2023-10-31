<?php
// TODO Add verification for the job owner
namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->input('skill');

        $skills = Skill::where('name', 'LIKE', "%{$searchTerm}%")
            ->limit(10)
            ->get();

        return response()->json($skills);
    }
    public function editUserSkills(Request $request)
    {
        $request->validate([
            'skills' => 'sometimes|array',
            'skills.*' => 'sometimes|string',
        ]);

        $skills = $request->skills;
        $user = $request->user();

        if (empty($skills)) {
            $user->skills()->detach();
            return redirect()
                ->route('profile.show')
                ->with(
                    'message',
                    json_encode([
                        'title' => 'Skill notification',
                        'content' => 'All skills removed successfully',
                        'status' => 'success',
                    ])
                );
        }

        $existingSkills = Skill::whereIn('name', $skills)
            ->pluck('id')
            ->all();

        $newSkills = array_diff(
            $skills,
            Skill::whereIn('name', $skills)
                ->pluck('name')
                ->all()
        );

        foreach ($newSkills as $skillName) {
            $skill = Skill::create(['name' => $skillName]);
            $existingSkills[] = $skill->id;
        }

        $user->skills()->sync($existingSkills);

        return redirect()
            ->route('profile.show')
            ->with(
                'message',
                json_encode([
                    'title' => 'Skill notification',
                    'content' => 'Skills edited successfully',
                    'status' => 'success',
                ])
            );
    }
    public function editJobSkills(Request $request, Job $job)
    {
        $request->validate([
            'skills' => 'sometimes|array',
            'skills.*' => 'sometimes|string',
        ]);

        $skills = $request->skills;
        $user = $request->user();

        if (empty($skills)) {
            $job->skills()->detach();
            return redirect()
                ->route('profile.show')
                ->with(
                    'message',
                    json_encode([
                        'title' => 'Skill notification',
                        'content' => 'All skills removed successfully',
                        'status' => 'success',
                    ])
                );
        }

        $existingSkills = Skill::whereIn('name', $skills)
            ->pluck('id')
            ->all();

        $newSkills = array_diff(
            $skills,
            Skill::whereIn('name', $skills)
                ->pluck('name')
                ->all()
        );

        foreach ($newSkills as $skillName) {
            $skill = Skill::create(['name' => $skillName]);
            $existingSkills[] = $skill->id;
        }

        $job->skills()->sync($existingSkills);

        return redirect()
            ->route('jobs.show', $job->slug)
            ->with(
                'message',
                json_encode([
                    'title' => 'Skill notification',
                    'content' => 'Skills edited successfully',
                    'status' => 'success',
                ])
            );
    }
}
