<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /*$users = \App\Models\User::pluck('id');
    $contacts = \App\Models\Contact::pluck('id');

    \App\Models\Projects::factory(35)->make()->each(function ($project) use($contacts, $users) {
      $project->contact_id = $contacts->random();
      $project->save();

      $project->users()
        ->attach($users->random(), ['role' => fake()->randomElement(['owner', 'member']), 'assigned_by' =>  $users->random()]);
    });*/

    \App\Models\Project::factory()
      ->count(25)
      ->create();
  }
}
