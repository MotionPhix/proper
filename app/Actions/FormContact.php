<?php

namespace App\Actions;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class FormContact
{
  public function __invoke(Contact $contact = null)
  {
    $user = Auth::user(); // get the authenticated user

    // check if the user has the 'update-contact' permission
    $hasUpdatePermission = $user->hasPermissionTo('update-contact');

    // check if the user owns the contact they are trying to update
    $ownsContact = $contact && $contact->user->id === $user->id;

    // check if the user has an 'admin' or 'manager' role
    $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

    // combine all the conditions using logical OR
    $isAuthorized = $isAdminOrManager || $ownsContact || $hasUpdatePermission;

    // set a default permission based on whether the user is authorized to update or create a contact
    $permission = $contact ? $isAuthorized : $user->hasPermissionTo('create-contact');

    if ($permission) {

      return response()->json([
        'contact' => $contact ?? new contact(),
        'companies' => \App\Models\Company::orderByName()->get()
      ]);

    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'message' => 'You are not allowed to' . ($contact ? " edit this " : " create ") . 'contact' . ($contact ? '' : 's') . '!'
    ]);
  }
}
