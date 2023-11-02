<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        $job_id = 1;

        $user_id = User::whereDoesntHave('applications', function ($query) use (
            $job_id
        ) {
            $query->where('job_id', $job_id);
        })
            ->inRandomOrder()
            ->value('id');

        if (is_null($user_id)) {
        }

        return [
            'job_id' => $job_id,
            'user_id' => $user_id,
            'status' => 'open',
            // 'status' => $this->faker->randomElement([
            //     'pending',
            //     'accepted',
            //     'rejected',
            // ]),
            'message' => null,
        ];
    }
}
