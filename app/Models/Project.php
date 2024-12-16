<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
  use HasFactory, BootUuid;

  protected $casts = [
    'start_date' => 'date:Y-m-d',
    'end_date' => 'date:j F, Y',
    'deadline' => 'date',
  ];

  public function company(): BelongsTo
  {
    return $this->belongsTo(Company::class);
  }

  public function contact(): BelongsTo
  {
    return $this->belongsTo(Contact::class);
  }

  public function owner(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function boards(): HasMany
  {
    return $this->hasMany(Board::class);
  }

  public function users() // users working on this project
  {
    return $this->belongsToMany(User::class, 'project_user')
      ->withPivot('role', 'assigned_by')
      ->withTimestamps();
  }

  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  public function isOwner(User $user)
  {
    return $this->users()
      ->wherePivot('user_id', $user->id)
      ->wherePivot('role', 'owner')
      ->exists();
  }

  public function files()
  {
    return $this->hasMany(\App\Models\File::class);
  }

  public function scopeForUser(Builder $query, User $user)
  {
    if ($user->hasPermissionTo('view-all-projects') || $user->hasAnyRole(['admin', 'general-manager'])) {

      return $query->with(['contact:id,company_id' => ['company:id,name'], 'files'])
        ->orderByDeadline();
    }

    $query->select(['id', 'contact_id', 'description', 'name', 'status'])
      ->whereHas('users', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      })
      ->with(['contact:id,company_id,first_name,last_name', 'contact.company:id,name'])
      ->orderByDeadline();
  }

  public function scopeUsersNotOnProject()
  {
    $users = \DB::table('users')
      ->whereNotIn('id', function ($query) {
        $query->select('user_id')
          ->from('project_user')
          ->where('project_id', $this->id);
      })
      ->get();

    return $users;
  }

  public function scopeWithBudget($query)
  {
    return $query->select('projects.*')
      ->selectSub(function ($subquery) {
        $subquery->from('tasks')
          ->whereColumn('tasks.project_id', 'projects.id')
          ->selectRaw('SUM(tasks.cost)');
      }, 'budget');
  }

  public function scopeOrderByDeadline(Builder $query)
  {
    return \DB::table('projects')
      ->leftJoin('tasks', 'tasks.project_id', '=', 'projects.id')
      ->select('projects.*', \DB::raw('COALESCE(CAST(MAX(tasks.end_date) AS DATETIME), projects.created_at) as deadline'))
      ->groupBy('projects.id')
      ->orderBy('deadline')
      ->orderBy('projects.created_at');
  }

  public function getProgressAttribute()
  {
    $totalTasks = $this->tasks()->count();

    if ($totalTasks == 0) {
      return 0;
    }

    $doneTasks = $this->tasks()->where('status', 'done')->count();

    return round($doneTasks / $totalTasks * 100);
  }

  public function getDeadlineAttribute()
  {
    $totalTasks = $this->tasks()->count();

    if ($totalTasks == 0) {
      return 0;
    }

    $doneTasks = $this->tasks()->where('status', 'done')->count();

    return round($doneTasks / $totalTasks * 100);
  }

  protected function budget(): Attribute
  {
    return Attribute::make(
      get: fn ($budget) => 'Mk ' . number_format($this->tasks()->sum('cost') / 100, 2),
      set: fn ($budget) => $budget * 100,
    );
  }

  public function getCommissionAttribute()
  {
    $owner = $this->owner->first();

    if ($owner && $owner->hasRole('sales-member')) {
      return 'Mk ' . number_format($this->tasks()->sum('cost') * (10 / 100) / 100, 2);
    }

    return 0;
  }

  public function getAssignableUsersAttribute()
  {
    $loggedInUserId = auth()->id();
    $excludedRoles = ['admin', 'general-manager'];

    $users = \DB::table('users')
      ->select(['users.id', 'users.name'])
      ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
      ->where('users.id', '<>', $loggedInUserId)
      ->whereNotIn('roles.slug', $excludedRoles)
      ->orderBy('users.name')
      ->groupBy('users.id')
      ->get();

    return $users;
  }

  public function scopeTransferOwnershipTo($newOwnerId)
  {
    $currentOwner = $this->owner->first();

    // Check if the current owner has any tasks on the project
    $hasTasks = $this->tasks()->where('user_id', $currentOwner->id)->exists();

    // If the current owner doesn't have tasks, remove them from the project_user table
    if (!$hasTasks && $currentOwner) {
      $this->users()->detach($currentOwner);
    }

    // Update the new owner's role to 'owner'
    $this->users()->syncWithoutDetaching([
      $newOwnerId => ['role' => 'owner'],
    ]);
  }
}
