<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use ProtoneMedia\Splade\Facades\Toast;

class DeleteTask
{

  public function __invoke(Task $task): RedirectResponse
  {
    $user = auth()->user();
    $project = $task->project;

    if ($user->hasAnyRole(['admin', 'general-manager']) || $project->isOwner($user)) {
      // Delete the task
      $task->delete();

      Toast::autoDismiss(5)->success('Task was permanently deleted.');

      return redirect()->back();
    }

    Toast::autoDismiss(5)->title('Unauthorised action')->danger('You are not allowed to delete this task!');

    return redirect()->back();
  }
}
