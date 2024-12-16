<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;

class OpenAddMember
{

  public function __invoke(Project $project): View|RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'general-manager']) || $project->isOwner($user) || $user->hasPermissionTo('add-team')) {
      return view('projects.add-member', [
        'project' => $project,
        'users' => $project->usersNotOnProject(),
      ]);
    }

    Toast::autoDismiss(10)->danger('You are not allowed to add members to this project');

    return redirect()->back();
  }
}
