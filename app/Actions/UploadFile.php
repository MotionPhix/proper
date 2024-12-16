<?php

namespace App\Actions;

use App\Models\File;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use ProtoneMedia\Splade\Facades\Toast;

class UploadFile
{
  public function __invoke(Request $request, Project $project): View|RedirectResponse
  {
    if (
      auth()->user()->hasPermissionTo('upload-files') ||
      $project->isOwner(auth()->user()) ||
      auth()->user()->hasAnyRole(['admin', 'general-manager'])
    ) {
      $files = $request->file('documents');

      foreach ($files as $file) {
        $originalFilename = $file->getClientOriginalName();
        // $generatedFilename = Str::random(20) . '.' . $file->getClientOriginalExtension();

        $path = $file->store('/', 'documents');

        $fileModel = new File;

        $fileModel->project_id = $project->id;
        $fileModel->user_id = auth()->user()->id;
        $fileModel->original_filename = $originalFilename;
        // $fileModel->generated_filename = $generatedFilename;
        $fileModel->path = $path;

        $fileModel->save();
      }

      Toast::autoDismiss(5)->success('File was uploaded successfully!');

      return redirect()->back();
    }

    Toast::autoDismiss(6)
      ->title('Shattered hope.')
      ->danger('You are not allowed to upload files on this project!');

    return redirect()->back();
  }
}
