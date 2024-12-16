<?php

namespace App\Actions\Reports;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class LoadReport
{

  public function __invoke(): Response
  {
    $projects = Project::forUser(auth()->user())->get();

    return  Inertia::render('Reports/Index', ['projects' => $projects, 'projectsCount' => $projects->count()]);
  }
}
