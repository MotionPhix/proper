<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ProjectRequest $request)
    {
        if (Gate::allows('create', auth()->user())) {

          $project = new Project;

          $project->name = $request->name;
          $project->description = $request->description;
          $project->contact_id = $request->contact_id;
          $project->company_id = $request->company_id;
          $project->status = 'open';

          $project->save();

          // assign the user as owner of the project on creation
          $project->users()->attach(auth()->user()->id, ['role' => 'owner']);

          // check if files were uploaded
          if ($request->file('documents')) {

            $files = $request->file('documents');

            foreach ($files as $file) {
              $originalFilename = $file->getClientOriginalName();
              $generatedFilename = Str::random(20) . '.' . $file->getClientOriginalExtension();

              $path = $file->storeAs('public/uploads', $generatedFilename);

              $fileModel = new File();
              $fileModel->project_id = $project->id;
              $fileModel->user_id = $request->user()->id;
              $fileModel->original_filename = $originalFilename;
              $fileModel->generated_filename = $generatedFilename;
              $fileModel->path = $path;

              $fileModel->save();
            }
          }

          $toastTitles = collect([
            'Well done!',
            'Great job!',
            'Awesome!',
            'Congratulations!',
            'Hooray!',
          ]);

          return redirect()->back()->with('toast', [
            'type' => 'success',
            'title' => $toastTitles->random(),
            'message' => 'Projects was successfully created!'
          ]);
        }

        return redirect()->back()->with('toast', [
          'type' => 'warning',
          'title' => 'No Permission',
          'message' => 'You are not allowed to create projects'
        ]);
    }
}
