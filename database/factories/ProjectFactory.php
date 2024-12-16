<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    $contact = \App\Models\Contact::inRandomOrder()->first();

    return [
      'name' => fake()->unique()->sentence(2),
      'description' => fake()->paragraph,
      'status' => fake()->randomElement(['open', 'closed']),
      'contact_id' => $contact->id,
      'company_id' => $contact->company->id,
    ];
  }

  public function configure()
  {
    return $this->afterCreating(function (\App\Models\Project $project) {
      // Assign project to a user
      $users = \App\Models\User::inRandomOrder()->limit(rand(1, 4))->get();

      // add some interractions
      $project->contact->interactions()->saveMany(\App\Models\Interaction::factory(rand(6, 15))->make([
        "project_id" => $project->id,
        "user_id" => $project->contact->user->id
      ]));

      foreach ($users as $user) {
        $project->users()->attach($user, ['role' => fake()->randomElement(['owner', 'member']), 'assigned_by' => \App\Models\User::inRandomOrder()->first()->id]);
      }

      // create a new board instance
      $board = new \App\Models\Board([
        'user_id' => $project->users->random()->id,
        'name' => fake()->sentence(rand(2, 3))
      ]);

      // add project to a board
      $project->boards()->save($board);
    });
  }
}
