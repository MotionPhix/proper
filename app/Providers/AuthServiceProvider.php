<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  protected $policies = [
    Project::class => ProjectPolicy::class,
  ];

  public function boot(): void
  {
    $this->registerPolicies();
  }
}
