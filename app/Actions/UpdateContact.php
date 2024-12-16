<?php

namespace App\Actions;

use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class UpdateContact
{

  public function __invoke(UpdateContactRequest $request, Contact $contact): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'general-manager']) || $contact->user->id === $user->id) {

      // Update existing contact_user record if new user id is provided
      if (!empty($request->user_id)) {
        $existingUser = $contact->users()
          ->where('user_id', $contact->user_id)
          ->where('company_id', $contact->company_id)
          ->first();

        if ($existingUser) {
          $existingUser->to_date = Carbon::now();
          $existingUser->save();
        }

        $contact->users()->create([
          'company_id' => $request->company_id,
          'user_id' => $request->user_id,
          'from_date' => Carbon::now(),
          'to_date' => null
        ]);
      }

      // Check if the company id has changed
      if ($contact->company_id !== $request->company_id) {

        /*$existingCompanyContact = $contact->companies()
          ->where('company_id', $contact->company_id)
          ->first();*/

        // $existingCompanyContact->pivot->to_date = Carbon::now();
        // $existingCompanyContact->pivot->save();

        $contact->companies()->updateExistingPivot($contact->company_id, ['to_date' => Carbon::now()]);

        $contact->companies()->attach($request->company_id, [
          'from_date' => Carbon::now(),
          'to_date' => null
        ]);

      }

      $contact->email = $request->email;
      $contact->save();

      return redirect()->back()
        ->with('toast', [
          'type' => 'success',
          'title' => 'You have done it!',
          'message' => 'Contact was successfully updated.'
        ]);
    }

    return redirect()->back()->with('toast', [
      'message' => 'You are not allowed to update this contact'
    ]);
  }
}
