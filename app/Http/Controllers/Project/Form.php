<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class Form extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Project $project = null)
  {
    $user = $request->user(); // get the authenticated user

    // Determine if the user is authorized to create or update the project
    $permission = $project ? $user->can('update', $project) : $user->can('create', Project::class);

    if ($permission) {
      return response()->json([
        'project' => $project ?? new Project(),
        'companies' => \App\Models\Company::select(['id', 'name'])->has('contacts')->get()
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'title' => $project ? 'You don\'t own the project' : 'Unauthorized attempt',
      'message' => 'You are not allowed to' . ($project ? " edit this " : " create ") . 'project' . ($project ? '' : 's') . '!'
    ]);
  }
}
