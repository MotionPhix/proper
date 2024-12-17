<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class Index extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $projects = Project::forUser($request->user())->get();

    return Inertia('Projects/Index', [
      'projects' => $projects, 'projectsCount' => $projects->count()
    ]);
  }
}
