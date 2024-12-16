<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Expression;

class Contact extends Model
{
  use HasFactory, BootUuid;

  public function company(): BelongsTo
  {
    return $this->belongsTo(Company::class);
  }

  public function interactions(): HasMany
  {
    return $this->hasMany(Interaction::class);
  }

  public function lastInteraction(): HasOne
  {
    return $this->hasOne(Interaction::class, 'id', 'last_interaction_id');
  }

  public function scopeWithLastInteraction(Builder $query)
  {
    $query->addSubSelect(
      'last_interaction_id',
      Interaction::select('id')
        ->whereRaw('contact_id = contacts.id')
        ->latest()
    )->with('lastInteraction');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'contact_user')
      ->withPivot(['from_date', 'to_date'])
      ->withTimestamps();
  }

  public function companies(): BelongsToMany
  {
    return $this->belongsToMany(Company::class, 'company_contact')
      ->withPivot(['from_date', 'to_date'])
      ->withTimestamps();
  }

  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }

  public function phoneNumbers(): MorphMany
  {
    return $this->morphMany(Phone::class, 'phoneable');
  }

  public function total()
  {
    return $this->forUser(auth()->user())->count();
  }

  public function scopeOrderByName(Builder $query)
  {
    $query->orderBy('first_name')->orderBy('last_name');
  }

  public function scopeForUser(Builder $query, User $user)
  {
    if ($user->hasAnyRole(['admin', 'general-manager'])) {
      return $query->with('company');
    }

    $query->whereHas('users', function ($query) use ($user) {
      $query->where('user_id', $user->id);
    })->with(['company' => function ($query) {
      $query->select('companies.id', 'companies.name');
    }])->select(['id', 'first_name', 'last_name', 'status', 'email', 'company_id']);
  }

  /**
   * Get the total revenue for the contact with the currently logged in user.
   *
   * @return float
   */
  public function getRevenueAttribute()
  {
    $user = auth()->user(); // logged in user

    $revenue = 0;

    /*if ($user->id === $this->user_id) {

      foreach ($this->projects as $project) {

        if ($user->id === $project->user_id) {

          $revenue += $project->tasks->sum('cost');

        }

      }

      return 'Mk ' . number_format($revenue / 100, 2);
    }

    foreach ($this->projects as $project) {
      if ($this->users->first(function ($u) use($user) { return $u->id === $user->id; })->id === $project->user_id) {
        $revenue += $project->tasks->sum('cost');
      }
    }*/

    /*if ($user->id === $this->user_id) {
      foreach ($this->projects as $project) {
        if ($user->id === $project->owner->id) {
          $revenue += $project->tasks->sum('cost');
        }
      }
    } else {
      foreach ($this->projects as $project) {
        $owner = $this->users->first(function ($u) use ($project) {
          return $u->id === $project->owner->id;
        });

        if ($owner && $owner->id === $user->id) {
          $revenue += $project->tasks->sum('cost');
        }
      }
    }*/

    // Check if the logged-in user has the necessary roles
    if ($user->hasAnyRole(['admin', 'general-manager'])) {

      // Loop through all projects of the currently viewed contact
      foreach ($this->projects as $project) {

        $revenue += $project->tasks->sum('cost');
      }
    } else {

      if ($this->projects->count()) {

        // Loop through all projects of the currently viewed contact
        foreach ($this->projects as $project) {

          // Check if the logged-in user and the currently viewed contact ever worked on a project together
          $userIds = $project->users->pluck('id')->toArray();

          if ($project->isOwner($user) && in_array($this->user_id, $userIds)) {

            $revenue += $project->tasks->sum('cost');
          }
        }
      } else {

        return "No project's found for $this->first_name yet.";
      }
    }

    return 'Mk ' . number_format($revenue / 100, 2);
  }
}
