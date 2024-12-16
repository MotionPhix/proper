<?php

namespace App\Actions\Companies;

use App\Models\Company;
use Inertia\Inertia;

class LoadCompanies
{

  public function __invoke()
  {
    $companies = Company::forUser(auth()->user())->paginate(10);

    return Inertia::render('Company/CompanyIndex', ['companies' => $companies]);
  }
}
