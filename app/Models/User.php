<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, BootUuid, HasRoles;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Full Name Accessor
   *
   * @return string
   */
  public function getFullNameAttribute(): string
  {
    return "{$this->first_name} {$this->last_name}";
  }

  /**
   * Relationships
   */

  // A user may be the owner of multiple projects
  public function ownedProjects(): HasMany
  {
    return $this->hasMany(Project::class, 'owner_id');
  }

  // A user can be assigned to multiple projects
  public function assignedProjects(): BelongsToMany
  {
    return $this->belongsToMany(Project::class, 'project_user')
      ->withPivot('role', 'assigned_by')
      ->withTimestamps();
  }

  // A user may have created multiple tasks
  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  // A user can be associated with multiple contacts
  public function contacts(): HasMany
  {
    return $this->hasMany(Contact::class);
  }

  /**
   * Custom Methods
   */

  /**
   * Check if the user is an admin
   *
   * @return bool
   */
  public function isAdmin(): bool
  {
    return $this->hasRole('admin');
  }

  /**
   * Check if the user is the owner of a project
   *
   * @param Project $project
   * @return bool
   */
  public function isOwnerOfProject(Project $project): bool
  {
    return $this->id === $project->owner_id;
  }

  /**
   * Check if the user is assigned to a project
   *
   * @param Project $project
   * @return bool
   */
  public function isAssignedToProject(Project $project): bool
  {
    return $this->assignedProjects->contains($project);
  }

  public function phones()
  {
    return $this->morphMany(Phone::class, 'model');
  }

  public function avatarUrl()
  {
    return $this->avatar
      ? Storage::disk('avatars')->url($this->avatar)
      : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
  }
}
