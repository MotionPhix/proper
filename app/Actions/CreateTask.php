<?php

namespace App\Actions;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class CreateTask
{
  public function __invoke(StoreTaskRequest $request): RedirectResponse
  {
    if (auth()->user()->hasPermissionTo('create-task') || auth()->id() === $request->user_id) {

      $task = new Task;

      $task->title = $request->title;
      $task->description = $request->description;
      $task->cost = $request->cost;
      $task->user_id = $request->user_id;
      $task->board_id = $request->board_id;
      $task->project_id = $request->project_id;
      $task->status = $request->status ? $request->status : 'new';
      $task->start_date = $request->start_date;
      $task->end_date = $request->end_date;

      $task->save();

      // assign the user as owner of the task on creation
      $project = $task->project;

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

      $toastTitles = collect([
        'Well done!',
        'Great job!',
        'Awesome!',
        'Congratulations!',
        'Hooray!',
      ]);

      return redirect()->back()->with('toast', [
        'type' => 'success',
        'title' => $toastTitles->random(),
        'message' => 'Task was successfully created!'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'title' => 'You don\'t own the project',
      'message' => 'You are not allowed to create tasks on this project'
    ]);

  }
}
