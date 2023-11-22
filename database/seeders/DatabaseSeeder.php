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

        \App\Models\User::factory(50)->create();

        \App\Models\JobLevel::factory()->create([
            'level' => 'Internship',
        ]);
        \App\Models\JobLevel::factory()->create([
            'level' => 'Junior',
        ]);
        \App\Models\JobLevel::factory()->create([
            'level' => 'Mid',
        ]);
        \App\Models\JobLevel::factory()->create([
            'level' => 'Senior',
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

        \App\Models\Job::factory(300)->create();
        // \App\Models\Application::factory(100)->create();
    }
}
