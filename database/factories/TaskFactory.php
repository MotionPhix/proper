<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'title' => fake()->sentence(rand(2, 3)),
      'description' => fake()->paragraph,
      'cost' => random_int(1999999, 5999999), // fake()->numberBetween(1000000, 5000000),
      'due_date' => fake()->dateTimeBetween('now', '+2 weeks'),
      'status' => fake()->randomElement(['todo', 'in-progress', 'done'])
    ];
  }
}
