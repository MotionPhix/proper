<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Show extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Project $project)
    {
      $user = auth()->user(); // get the authenticated user

      // check if the user has the 'view-project' permission
      $hasViewPermission = $user->hasPermissionTo('view-project');

      // check if the user owns the project they are trying to view
      $ownsProject = $project->isOwner($user);

      // check if the user is part of the project's team
      $isMember = $project->users->contains($user);

      // check if the user has an 'admin' or 'manager' role
      $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

      // combine all the conditions using logical OR
      $isAuthorized = $isAdminOrManager || $ownsProject || $isMember || $hasViewPermission;

      if ($isAuthorized) {
        return Inertia::render('Projects/ShowProject', [
          'project' => $project->load(['owner', 'contact.company', 'users', 'boards.tasks' => fn($query) => $query->orderBy('position')])
        ]);
      }

      return redirect()->route('projects.index')->with('toast', [
        'type' => 'danger',
        'title' => 'Unauthorised action',
        'message' => 'You are not allowed to view this project!'
      ]);
    }
}
