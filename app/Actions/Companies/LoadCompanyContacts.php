<?php

namespace App\Actions\Companies;

use App\Models\Company;

class LoadCompanyContacts
{
  public function __invoke(Company $company)
  {
    return response()->json([
      'contacts' => $company->contacts
    ]);
  }

  // return response()->json(['toast' => 'contacts was deleted'])->header('X-Inertia-Location', url()->previous());
}
