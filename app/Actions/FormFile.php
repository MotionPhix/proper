<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;

class FormFile
{
  public function __invoke(Project $project): View|RedirectResponse
  {
    if (
        auth()->user()->hasPermissionTo('upload-files') ||
        auth()->user()->id == $project->owner->first()->id ||
        auth()->user()->hasAnyRole(['admin', 'general-manager'])
    ) {
      return view('files.upload', [
        'project' => $project
      ]);
    }

    Toast::autoDismiss(6)
      ->title('Messing up the files.')
      ->danger('You are not allowed to upload files on this project!');

    return redirect()->back();
  }
}
