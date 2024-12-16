<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;

class OpenTransferProject
{

  public function __invoke(Project $project): View|RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
      return view('projects.transfer', [
        'project' => $project->id,
        'users' => $project->assignableUsers,
        'owner' => $project->owner()->first() ?? new User
      ]);
    }

    Toast::autoDismiss(10)->danger('You are beyond your limits. Tread carefully always');

    return redirect()->back();
  }
}
