<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;

class TransferProject
{

  public function __invoke(Request $request): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasRole('admin')) {

      $project = Project::findOrFail($request->project);

      $currentOwner = $project->owner->first();

      // Check if the current owner has any tasks on the project
      $hasTasks = $project->tasks()->where('user_id', $currentOwner->id)->exists();

      if ($currentOwner) {
        $project->users()->updateExistingPivot($currentOwner->id, ['role' => 'member']);
      }

      // If the current owner doesn't have tasks, remove them from the project_user table
      if (!$hasTasks && $currentOwner) {
        $project->users()->detach($currentOwner);
      }

      $project->users()->syncWithoutDetaching([
        $request->transferee => ['role' => 'owner'],
      ]);

      $newOwner = User::findOrFail($request->transferee);

      Toast::autoDismiss(5)
        ->title('Great snakes!')
        ->success('Ownership was successfully transferred! ' . $newOwner->name . ' now owns this project.');

      return redirect()->back();
    }

    Toast::autoDismiss(10)->title('Tread carefully!')->danger('You are beyond your limits. Tread carefully always');

    return redirect()->back();
  }
}
