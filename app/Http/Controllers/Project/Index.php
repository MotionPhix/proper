<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Index extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $projects = Project::forUser(auth()->user())->paginate(15);

    return Inertia::render('Projects/Index', [
      'projects' => $projects
    ]);
  }
}
