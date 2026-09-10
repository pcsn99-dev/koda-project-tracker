<?php

namespace Database\Factories;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {


        $startDate = fake()->dateTimeBetween('-2 months', '+1 month');
        $dueDate = fake()->dateTimeBetween($startDate, '+6 months');


        return [
            'client_name' => fake()->company(),
            'project_name' => fake()->words(3, true),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(ProjectStatus::cases())->value,
            'priority' => fake()->randomElement(ProjectPriority::cases())->value,
            'start_date' => $startDate,
            'due_date' => $dueDate,
        ];



    }


    
}