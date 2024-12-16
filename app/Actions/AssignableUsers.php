<?php

namespace App\Actions;

use App\Models\Project;

class AssignableUsers
{
  public function __invoke(Project $project)
  {
    return response()->json([
      'users' => $project->assignableUsers
    ]);
  }

  // return response()->json(['toast' => 'contacts was deleted'])->header('X-Inertia-Location', url()->previous());
}
