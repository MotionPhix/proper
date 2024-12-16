<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
  use HandlesAuthorization;

  /**
   * Create a new policy instance.
   */
  public function __construct()
  {
  }

  /** * Determine whether the user can view any projects. */
  public function viewAny(User $user)
  {
    return $user->hasPermissionTo('read projects');
  }

  /** * Determine whether the user can view the project. */
  public function view(User $user, Project $project)
  {
    return $user->hasPermissionTo('read project')
      && ($user->hasAnyRole(['admin', 'manager', 'director'])
        || $project->isOwner($user)
        || $project->isAssignedTo($user));
  }

  /** * Determine whether the user can create projects. */
  public function create(User $user)
  {
    return $user->hasPermissionTo('create project');
  }

  /** * Determine whether the user can update the project. */
  public function update(User $user, Project $project)
  {
    return $user->hasPermissionTo('edit project')
      && ($user->hasAnyRole(['admin', 'manager', 'director'])
        || $project->isOwner($user));
  }

  /** * Determine whether the user can delete the project. */
  public function delete(User $user, Project $project)
  {
    return $user->hasPermissionTo('delete project')
      && ($user->hasAnyRole(['admin', 'manager', 'director'])
        || $project->isOwner($user));
  }
}
