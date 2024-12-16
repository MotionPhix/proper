<?php

namespace App\Actions;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class CreateContact
{
  public function __invoke(StoreContactRequest $request): RedirectResponse
  {
    if (auth()->user()->hasPermissionTo('create-contact')) {
      $contact = new Contact();

      $contact->first_name = $request->first_name;
      $contact->last_name = $request->last_name;
      $contact->email = $request->email;
      $contact->status = $request->status;
      $contact->company_id = $request->company_id;
      $contact->user_id = $request->user()->id;

      $contact->save();

      if ($request->phones) {
        foreach ($request->phones as $phone_type => $phone) {
          $contact->phoneNumbers()->create([
            'number' => $phone,
            'type' => $phone_type
          ]);
        }
      }

      $user = $request->user();

      // assign contact to the authenticated user
      $user->contacts()->attach($contact->id, [
        'company_id' => $request->company_id,
        'from_date' => Carbon::now(),
        'to_date' => null
      ]);

      // assign contact to the company in the $request
      $contact->companies()->attach($request->company_id, [
        'from_date' => Carbon::now(),
        'to_date' => null
      ]);

      return redirect()->route('contacts.index')->with('toast', [
        'type' => 'success',
        'message' => 'Contact was successfully created!'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'message' => 'You are not allowed to create contacts'
    ]);
  }
}
