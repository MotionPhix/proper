<?php

namespace App\Actions;

use App\Models\Contact;
use App\Models\Interaction;
use App\Models\Phone;
use App\Models\Project;
use Inertia\Inertia;

class ShowContact
{

  public function __invoke(Contact $contact, $modal = null)
  {

    $user = auth()->user(); // get the authenticated user

    // check if the user has the 'view-contact' permission
    $hasViewPermission = $user->hasPermissionTo('view-contact');

    // check if the user owns the contact they are trying to view
    $ownsContact = $contact->users->contains($user);

    // check if the user has an 'admin' or 'manager' role
    $isAdminOrManager = $user->hasAnyRole(['admin', 'general-manager']);

    // combine all the conditions using logical OR
    $isAuthorized = $isAdminOrManager || $ownsContact || $hasViewPermission;

    if ($isAuthorized) {
      $contact = $contact->load(['companies:id,name', 'company:id,name', 'interactions', 'user:id,first_name,last_name', 'projects', 'phoneNumbers']);

      $contact_transformed = collect([$contact])->map(function (Contact $instance) use($user, $isAdminOrManager) {
        return [
          'idx' => $instance->id,
          'full_name' => $instance->first_name . ' ' . $instance->last_name,
          'email' => $instance->email,
          'is_active' => $instance->status === 'active',
          'revenue' => $instance->revenue,
          'user' => $user->id === $instance->user_id || $isAdminOrManager ? $instance->user : $instance->users->first(function ($user_instance) use($user) { return $user->id === $user_instance->id; }),
          'company' => $user->id === $instance->user_id || $isAdminOrManager ? $instance->company : $instance->companies->first(function ($company_instance) use($user, $instance) { return $user->id === $company_instance->pivot->user_id && $instance->id === $company_instance->pivot->contact_id; }),
          'phones' => $instance->phoneNumbers->transform(function (Phone $phone) { return ['number' => $phone->number]; }),
          'interactions' => $instance->interactions->transform(function (Interaction $interaction) { return ['topic' => $interaction->description, 'type' => $interaction->type, 'interacted_on' => $interaction->created_at->diffForHumans(), 'interaction_date' => $interaction->created_at->format('d M, Y')]; }),
          // 'projects' => $isAdminOrManager ? $instance->projects : $instance->projects->transform(function ($project) use($user, $instance) { $userIds = $project->users->pluck('id')->toArray(); if($project->isOwner($user) && in_array($user->id, $userIds)) return $project; }),
        ];
      })->first();

      // return response()->json($contact_transformed);

      return Inertia::render('Contact/ShowContact', [
        'contact' => $contact_transformed,
        'contacts' => $isAdminOrManager ? $contact->forUser($user)->get() : $user->contacts->load('company'),
        'modal' => $modal
      ]);
    }

    return redirect()->back()->with('toast', ['danger' => 'You are not allowed to view this contact!']);
  }
}
