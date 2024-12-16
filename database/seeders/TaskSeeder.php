<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $projects = \App\Models\Project::get();

    \App\Models\Task::factory(50)->make()->each(function ($task) use ($projects) {

      $project = $projects->random();

      $task->user_id = $project->users->random()->id;
      $task->project_id = $project->id;
      $task->board_id = $project->boards->random()->id;

      $task->save();

      // Check if the user is already in the project_user table
      if (!$project->users->contains($task->user_id)) {
        // Check if there are any owners in the project_user table
        $ownerCount = $project->users->where('pivot.role', 'owner')->count();

        // If there are no owners, assign the user who added the task as the owner
        if ($ownerCount == 0) {
          // If the user is not already in the project_user table, add them
          $project->users()->attach($task->user_id, ['role' => 'owner']);
          // $project->users()->updateExistingPivot($task->user_id, ['role' => 'owner']);
        } else {
          // If there are owners, assign the user who added the task as a member
          // $project->users()->updateExistingPivot($task->user_id, ['role' => 'member']);

          $project->users()->attach($task->user_id, ['role' => 'member']);
        }
      }
    });
  }
}
