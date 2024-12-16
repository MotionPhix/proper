<?php

namespace App\Actions;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;

class DeleteContact
{

  public function __invoke(Contact $contact): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasRole('admin') || $contact->id === $user->id) {
      // Delete all projects associated with the contact
      $projects = $contact->projects;

      foreach ($projects as $project) {
        $project->tasks->each(function ($task) { $task->delete(); });
        $project->delete();
      }

      $contact->delete();

      return redirect()->route('contacts.index')->with('toast', [
        'type' => 'success',
        'message' => 'contact was permanently deleted.'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'message' => 'You are not allowed to delete this contact!',
      'title' => 'Unauthorised action'
    ]);
  }
}
