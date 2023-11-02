<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle,
            'company_id' => 1,
            'job_type_id' => 1,
            'location' => $this->faker->city,
            'salary' => $this->faker->numberBetween(1000, 3000),
            'description' => $this->faker->paragraph,
        ];
    }
}
