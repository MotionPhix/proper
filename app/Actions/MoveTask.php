<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class MoveTask
{
  public function __invoke(Task $task): RedirectResponse
  {
    $board = \App\Models\Board::find(request()->board_id);

    if (auth()->user()->hasPermissionTo('move-task') || auth()->id() === $board->user_id) {

      $task->position = round(request()->position, 5);
      $task->board_id = request()->board_id;

      $task->save();

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
        'message' => 'Task was successfully moved!'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'title' => 'Permission Required!',
      'message' => 'You are not allowed to move this task'
    ]);

  }
}
