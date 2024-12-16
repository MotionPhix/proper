<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction>
 */
class InteractionFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'type' => fake()->randomElement([
        'Phone',
        'Email',
        'SMS',
        'Meeting',
        'Breakfast',
        'Lunch',
        'Dinner',
        'Twitter',
        'Facebook',
        'LinkedIn',
        'Viewed Invoice',
        'Paid Invoice',
      ]),
      'description' => fake()->paragraph(1),
      'created_at' => fake()->date(fake()->randomElement([now()->subWeeks(), now()->subMonths(), now()->subYears()])),
      'updated_at' => fake()->date(fake()->randomElement([now()->subWeeks(), now()->subMonths(), now()->subYears()])),
    ];
  }
}
