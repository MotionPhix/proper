<?php

namespace App\Actions;

use App\Models\Task;
use Inertia\Inertia;
use Inertia\Response;

class ListTasks
{

  public function __invoke(): Response
  {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'general-manager'])) {
      $tasks = Task::with('project:id,name', 'user:id,name')->paginate();
    } else {
      $tasks = $user->tasks->load('project:id,name', 'user:id,name', 'board:id,name');
    }

    /*$tasks = Task::with('project:id,name,end_date', 'user:id,name', 'board:id,name')
      ->when(!$user->hasAnyRole(['admin', 'general-manager']), function ($query) use ($user) {
        return $query->whereHas('board', function ($query) use ($user) {
          $query->where('team_id', $user->currentTeam->id);
        })->whereHas('users', function ($query) use ($user) {
          $query->where('user_id', $user->id);
        });
      })
      ->get()
      ->groupBy('board_id');*/

    // if ($user->hasAnyRole(['admin', 'general-manager'])) {
    //   $tasks = Task::with('project:id,name', 'user:id,name', 'board:id,name')
    //     ->paginate();
    // } else {
    //   $tasks = $user->tasks
    //     ->load('project:id,name', 'user:id,name', 'board:id,name');
    // }

    return Inertia::render('Tasks/Index', [
      'tasks' => $tasks
    ]);
  }
}
