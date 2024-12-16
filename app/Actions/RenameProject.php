<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RenameProject
{

  public function __invoke(Request $request, Project $project): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasRole('admin') || $user->hasPermissionTo('update-project') || $project->isOwner($user)) {

      $valid_data = $request->validate([
        'name' => 'required|string'
      ]);

      $old_name = $project->name;

      if ($project->name === $request->name) return redirect()->back();

      $project->name = $request->name;
      $project->save();

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
        'message' => 'You have successfully renamed the project from ' . $old_name . ' to ' . $request->name
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'danger',
      'title' => 'Can\'t update project!',
      'message' => 'You are beyond your limits. You don\'t own this project!'
    ]);
  }
}
