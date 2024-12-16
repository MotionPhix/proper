<?php

namespace App\Actions;

use App\Models\Board;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DeleteProject
{

  public function __invoke(Project $project): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasRole('admin') || $project->isOwner($user)) {
      if ($project->files) {
        // Delete all files associated with the project
        $files = $project->files;

        foreach ($files as $file) {
          Storage::delete($file->path);
          $file->delete();
        }
      }

      // Delete all tasks associated with the project
      Task::where('project_id', $project->id)->delete();

      // Delete all boards associated with the project
      Board::where('project_id', $project->id)->delete();

      $project->delete();

      $toastTitles = collect([
        "Projects Deleted",
        "Mission Accomplished",
        "Projects Annihilated",
        "Projects Obliterated",
        "Projects Expunged",
        "Projects Eradicated",
        "Projects Disintegrated",
        "Projects Exterminated",
        "Projects Vanished"
      ]);

      return redirect()->back()->with('toast', [
        'type' => 'success',
        'title' => $toastTitles->random(),
        'message' => 'Projects was permanently deleted.'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'message' => 'You are not allowed to delete this project!',
      'title' => 'Unauthorised action'
    ]);
  }
}
