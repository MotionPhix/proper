<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;

class ShowTask
{

  public function __invoke(Task $task): View|RedirectResponse
  {
    $user = Auth::user(); // get the authenticated user
    $project = $task->project; // get the project for this task

    // check if the user has the 'view-project' permission
    $hasViewPermission = $user->hasPermissionTo('view-project');

    // check if the user owns the project they are trying to view
    $ownsProject = $project->isOwner($user);

    // check if the user has an 'admin' or 'manager' role
    $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

    // combine all the conditions using logical OR
    $isAuthorized = $isAdminOrManager || $ownsProject || $hasViewPermission;

    if ($isAuthorized) {
      return view('tasks.show', ['task' => $task->load('user'), 'users' => $project->assignableUsers, 'project' => $project->id]);
    }

    Toast::autoDismiss(6)->title('Unauthorised attempt')
      ->danger('You are not allowed to view this task!');

    return redirect()->back();
  }
}
