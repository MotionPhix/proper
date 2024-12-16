<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Inertia\Inertia;

class LoadProjects
{

  public function __invoke($modal = null)
  {
    $projects = Project::forUser(auth()->user())->paginate(15);

    if ($modal) {

      $user = request()->user(); // get the authenticated user

      if ($user->hasAnyRole(['admin', 'general-manager']) || $user->hasPermissionTo('create-project')) {

        return Inertia::render('Projects/Index', [
          'projects' => $projects,
          'project' => new Project(),
          'companies' => \App\Models\Company::select(['id', 'name'])->has('contacts')->get(),
          'canOpen' => !!$modal
        ]);

      }

      return redirect()->back()->with('toast', [
        'type' => 'warning',
        'title' => 'Unauthorised attempt',
        'message' => 'You are not allowed to create  projects!'
      ]);

    }
  }
}
