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
    // Get the currently authenticated user
    $user = $request->user();

    // Use the `forUser` scope on the Project model
    $projects = Project::forUser($user)->with('company', 'contact')->paginate(10); // Paginate to avoid loading too many projects at once

    // Return response (Inertia.js response for SPA or JSON response for API)
    return inertia('Projects/Index', [
      'projects' => $projects
    ]);
  }
}
