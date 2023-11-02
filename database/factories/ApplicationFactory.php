<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        // Assume that $job_id of 1 exists and is being used for simplicity
        $job_id = 1;

        // Find a random user who does not have an application for job_id 1
        $user_id = User::whereDoesntHave('applications', function ($query) use (
            $job_id
        ) {
            $query->where('job_id', $job_id);
        })
            ->inRandomOrder()
            ->value('id');

        // In case there is no eligible user, you can handle it here
        if (is_null($user_id)) {
            // Create a new user or handle this case as per your logic
            // $user_id = User::factory()->create()->id;
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
