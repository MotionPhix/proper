<?php

namespace App\Actions\Users;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class LoadUsers
{

  public function __invoke(): Response
  {
    $users = User::paginate();

    return Inertia::render('Users/Index', ['users' => $users, 'userCount' => $users->count()]);
  }
}
