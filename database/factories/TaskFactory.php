<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        $due = Carbon::parse(fake()->dateTimeBetween('now', '+30 days'));
        return [
            'title'             => fake()->sentence(),
            'original_due_date' => $due->format('Y-m-d'),
            'due_date'          => $due->format('Y-m-d'),
            'user_id'           => User::factory(),
        ];
    }
}
