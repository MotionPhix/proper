<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;

class FormTask
{

  public function __invoke(Project $project, Task $task = null): View|RedirectResponse
  {
    $user = Auth::user(); // get the authenticated user

    // check if the user has the 'update-project' permission
    $hasUpdatePermission = $user->hasPermissionTo('update-task');

    // check if the user owns the project they are trying to update
    $ownsProject = $project && $project->isOwner($user);

    // check if the user has an 'admin' or 'manager' role
    $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

    // combine all the conditions using logical OR
    $isAuthorized = $isAdminOrManager || $ownsProject || $hasUpdatePermission;

    // set a default permission based on whether the user is authorized to update or create a project
    $permission = $project ? $isAuthorized : $user->hasPermissionTo('create-task');

    if ($permission) {
      return view('tasks.form', [
        'project' => $project->id,
        'task' => $task ?? new Task(),
        'users' => $project->assignableUsers
      ]);
    }

    Toast::autoDismiss(6)->title($task ? 'You don\'t own the project' : 'Unauthorised attempt')
      ->danger('You are not allowed to' . ($task ? " edit this " : " create ") . 'task' . ($task ? '' : 's') . '!');

    return redirect()->back();
  }
}
