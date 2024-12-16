<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ProjectForm extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Project $project = null)
    {
        // check if the user has the 'update-project' permission
        $hasUpdatePermission = auth()->user()->hasPermissionTo('update-project');

        // check if the user owns the project they are trying to update
        $ownsProject = $project && $project->isOwner(auth()->user());

        // check if the user has an 'admin' or 'manager' role
        $isAdminOrManager = auth()->user()->hasAnyRole(['admin', 'general-manager']);

        // combine all the conditions using logical OR
        $isAuthorized = $isAdminOrManager || $ownsProject || $hasUpdatePermission;

        if (Gate::allows('create', auth()->user()) || $isAuthorized) {

            // return response()->json([
            //     'project' => $project ?? new Projects(),
            //     'companies' => \App\Models\Company::select(['id', 'name'])->has('contacts')->get()
            // ]);

            return Inertia::render('Projects/Create', [
              'project' => $project ?? new Project(),
              'companies' => \App\Models\Company::select(['id', 'name'])->has('contacts')->get()
            ]);
        }

        return redirect()->back()->with('toast', [
            'type' => 'warning',
            'title' => $project ? 'You don\'t own the project' : 'Unauthorised attempt',
            'message' => 'You are not allowed to' . ($project ? " edit this " : " create ") . 'project' . ($project ? '' : 's') . '!'
        ]);
    }
}
