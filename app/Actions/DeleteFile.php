<?php

namespace App\Actions;

use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\Splade\Facades\Toast;

class DeleteFile
{

  public function __invoke(File $file): RedirectResponse
  {
    $user = auth()->user();
    $project = $file->project;

    if ($user->hasRole('admin') || $project->isOwner($user) || $file->user_id === $user->id) {
      // Assuming you have a $file variable containing the file's information
      Storage::disk('documents')->delete($file->path);

      // Then you can delete the file record from the database
      $file->delete();

      Toast::autoDismiss(5)->danger('Your file has been successfully deleted!');

      return redirect()->back();
    }

    Toast::autoDismiss(5)->title('Unauthorised action')->danger('You are not allowed to delete this file!');

    return redirect()->back();
  }
}
