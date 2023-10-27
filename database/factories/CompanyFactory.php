<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'code' => Str::upper(Str::random(10)),
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->companyEmail,
            'country' => $this->faker->country,
            'state' => $this->faker->state,
            'town' => $this->faker->city,
            'address' => $this->faker->address,
            'logo' => 'no-logo',
            'logo_extension' => 'jpg',
            'description' => $this->faker->paragraphs(3, true),
            'owner' => \App\Models\User::query()
                ->inRandomOrder()
                ->first()->id,
        ];
    }
}
