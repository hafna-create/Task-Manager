<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Admin;
use App\Models\Project; 
use App\Models\Staff; 


/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'assigned_to' => Staff::factory(),
            'created_by' => Admin::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(Task::STATUSES),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
