<?php

namespace App\Actions;

use App\Events\TaskUpdated;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UpdateTask
{

  public function __invoke(UpdateTaskRequest $request, Task $task): RedirectResponse
  {
    $user = auth()->user();
    $project = $task->project;

    // Get the current user assigned to the task
    $currentUser = $task->user;

    // Get the new user to be re-assigned the task
    $newUser = User::findOrFail($request->user_id);

    // Get the list of users already assigned to the project
    $projectUsers = $project->users;

    if ($user->hasAnyRole(['admin', 'general-manager']) || $project->isOwner($user) || $task->user_id === $user->id) {
      $task->title = $request->title;
      $task->description = $request->description;
      $task->status = $request->status;
      $task->cost = $request->cost;
      $task->start_date = $request->start_date;
      $task->end_date = $request->end_date;

      if ($task->user_id !== $request->user_id && !$projectUsers->contains($newUser)) { // task was reassigned
        // Check if the previous user has any other tasks assigned to the project
        $hasOtherTasks = $projectUsers->where('id', $currentUser->id)
          ->where('pivot.role', 'member')
          ->where('tasks.id', '<>', $task->id)
          ->count() > 0;

        if (!$hasOtherTasks) {
          if (!$project->isOwner($task->user)) {
            // Remove the previous user from the project_user pivot table if he is not the owner of the projct
            $project->users()->detach($currentUser->id);
          }

          // Add the new user to the project_user pivot table
          $project->users()->attach($newUser->id, ['role' => 'member', 'assigned_by' => $user->id]);
        }

        // Update the task with the new user
        $task->user_id = $request->user_id;
      }

      $task->save();

      // Dispatch the TaskUpdated event
      event(new TaskUpdated($task));

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
        'message' => 'Task was successfully updated!'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'title' => 'Bad Intentions',
      'message' => 'You are not allowed to update this task'
    ]);
  }
}
