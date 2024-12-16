<?php

namespace App\Actions;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use ProtoneMedia\Splade\Facades\Toast;

class UpdateProject
{

  public function __invoke(UpdateProjectRequest $request, Project $project): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'general-manager']) || $project->isOwner($user)) {

      if (!is_null($request->member_id)) { // if you want to add member to the project's team
        $project->users()->attach($request->member_id, ['role' => 'member']);
      } else {
        $project->name = $request->name;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->customer_id = $request->customer_id;

        $project->save();
      }

      // check if files were uploaded
      if ($request->file('documents')) {

        $files = $request->file('documents');

        foreach ($files as $file) {
          $originalFilename = $file->getClientOriginalName();
          $generatedFilename = Str::random(20) . '.' . $file->getClientOriginalExtension();

          $path = $file->storeAs('uploads', $generatedFilename);

          $fileModel = new File;
          $fileModel->project_id = $project->id;
          $fileModel->original_filename = $originalFilename;
          $fileModel->generated_filename = $generatedFilename;
          $fileModel->path = $path;

          $fileModel->save();
        }
      }

      Toast::autoDismiss(5)
        ->success(
            is_null($request->member_id)
            ? 'Projects was successfully updated!'
            : 'Member was added successfully!'
          );

      return redirect()->back();
    }

    Toast::autoDismiss(10)
      ->danger(
        is_null($request->member_id)
          ? 'You are not allowed to update this project'
          : 'You are not allowed to add members to this project'
        );

    return redirect()->back();
  }
}
