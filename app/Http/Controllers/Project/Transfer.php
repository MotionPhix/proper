<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class Transfer extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Project $project)
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
