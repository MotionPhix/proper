<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ListUsers
{

  public function __invoke(): Response
  {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'manager'])) {
      $users = User::paginate();

      return Inertia::render('Users/Index', [
        'users' => $users,
      ]);

    }
  }
}
