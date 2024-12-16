<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskUpdatedListener implements ShouldQueue
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  \App\Events\TaskUpdated  $event
   * @return void
   */
  public function handle(TaskUpdated $event)
  {
    $project = $event->task->project;

    // Count the number of tasks that are not cancelled or done
    $incompleteTasks = $project->tasks()->whereNotIn('status', ['cancelled', 'done'])->count();

    // If all tasks are either cancelled or done, set the project status accordingly
    if ($incompleteTasks === 0) {
      $cancelledTasks = $project->tasks()->where('status', '=', 'cancelled')->count();
      $doneTasks = $project->tasks()->where('status', '=', 'done')->count();

      if ($cancelledTasks === 0 && $doneTasks > 0) {
        $project->status = 'closed';
      } elseif ($cancelledTasks > 0 && $doneTasks === 0) {
        $project->status = 'cancelled';
      }
    } else {
      $project->status = 'open';
    }

    $project->save();
  }
}
