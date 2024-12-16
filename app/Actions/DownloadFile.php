<?php

namespace App\Actions;

use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\Splade\Facades\Toast;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadFile
{

  public function __invoke(File $file): BinaryFileResponse|RedirectResponse
  {
    $user = auth()->user();
    $project = $file->project;

    if ($user->hasAnyRole(['admin', 'general-manager']) || $project->isOwner($user) || $file->user_id === $user->id) {

      $headers = [
        'Content-Type' => 'application/octet-stream',
      ];

      Toast::autoDismiss(5)->danger('Your file has been successfully downloaded!');

      return response()->download($file->getFullPath(), $file->original_filename, $headers);
    }

    Toast::autoDismiss(5)->title('Unauthorised action')->danger('You are not allowed to download this file!');

    return redirect()->back();
  }
}
