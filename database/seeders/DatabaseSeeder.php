<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        \App\Models\Company::factory()->create([
            'name' => 'Test Company',
        ]);
        \App\Models\JobExperience::factory()->create([
            'name' => 'Internship',
        ]);
        \App\Models\JobExperience::factory()->create([
            'name' => 'Junior',
        ]);
        \App\Models\JobExperience::factory()->create([
            'name' => 'Mid',
        ]);
        \App\Models\JobExperience::factory()->create([
            'name' => 'Senior',
        ]);
        \App\Models\JobType::factory()->create([
            'type' => 'Remote',
        ]);
        \App\Models\JobType::factory()->create([
            'type' => 'Hybrid',
        ]);
        \App\Models\JobType::factory()->create([
            'type' => 'On-Site',
        ]);
    }
}
