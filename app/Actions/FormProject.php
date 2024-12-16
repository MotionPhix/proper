<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FormProject
{

  public function __invoke(Project $project = null)
  {
    $user = Auth::user(); // get the authenticated user

    // check if the user has the 'update-project' permission
    $hasUpdatePermission = $user->hasPermissionTo('update-project');

    // check if the user owns the project they are trying to update
    $ownsProject = $project && $project->isOwner($user);

    // check if the user has an 'admin' or 'manager' role
    $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

    // combine all the conditions using logical OR
    $isAuthorized = $isAdminOrManager || $ownsProject || $hasUpdatePermission;

    // set a default permission based on whether the user is authorized to update or create a project
    $permission = $project ? $isAuthorized : $user->hasPermissionTo('create-project');

    if ($permission) {

      return response()->json([
        'project' => $project ?? new Project(),
        'companies' => \App\Models\Company::select(['id', 'name'])->has('contacts')->get()
      ]);

    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'title' => $project ? 'You don\'t own the project' : 'Unauthorised attempt',
      'message' => 'You are not allowed to' . ($project ? " edit this " : " create ") . 'project' . ($project ? '' : 's') . '!'
    ]);
  }
}
