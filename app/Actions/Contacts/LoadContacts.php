<?php

namespace App\Actions\Contacts;

use App\Models\Contact;
use Inertia\Inertia;

class LoadContacts
{

  public function __invoke($modal = null)
  {
    $user = auth()->user(); // get the authenticated user
    $contacts = Contact::forUser(auth()->user())->paginate(10);

    if ($modal) {

      // check if the user has an 'admin' or 'manager' role
      $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

      if ($isAdminOrManager || $user->hasPermissionTo('create-contact')) {
        // $statuses = App\Models\Task::STATUSES as $value => $label

        return Inertia::render('Contact/IndexContact', [
          'contact' => new contact(),
          'contacts' => $contacts,
          'companies' => \App\Models\Company::orderByName()->get(),
          'canOpen' => !!$modal
        ]);

      }

      return redirect()->back()->with('toast', [
        'type' => 'danger',
        'message' => 'You are not allowed to create contacts!'
      ]);

    } else {

      // return response()->json($contacts);

      return Inertia::render('Contact/Index', [
        'contacts' => $contacts,
        'canOpen' => !!$modal
      ]);
    }
  }
}
