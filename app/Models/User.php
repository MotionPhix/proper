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

  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }

  public function ownedProjects()
  {
    return $this->hasManyThrough(
      Project::class,
      ProjectUser::class,
      'user_id',
      'id',
      'id',
      'project_id'
    )->where('role', 'owner');
  }

  public function assignedProjects(): BelongsToMany
  {
    return $this->belongsToMany(Project::class, 'project_user')
      ->where('role', '!=', 'owner')
      ->withPivot('assigned_by')
      ->using(ProjectUser::class)
      ->as('assignment')
      ->withTimestamps();
  }

  public function tasks()
  {
    return $this->hasMany(Task::class);
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
  }

  public function permissions()
  {
    return $this->hasManyThrough(
      Permission::class,
      Role::class,
      'id',
      'id',
      'role_id',
      'permission_id'
    );
  }

  public function contacts()
  {
    return $this->belongsToMany(Contact::class, 'contact_user')->withPivot(['from_date', 'to_date'])->withTimestamps();
  }

  public function phoneNumbers()
  {
    return $this->morphMany(Phone::class, 'phoneable');
  }

  public function syncRoles($roles)
  {
    $this->roles()->sync($roles);
  }

  public function hasRole($role)
  {
    return $this->roles()->where('slug', $role)->exists();
  }

  public function hasAnyRole($roles)
  {
    return $this->roles()->whereIn('slug', $roles)->exists();
  }

  public function hasPermissionTo($permissionSlug)
  {
    return $this->roles->reduce(function ($carry, $role) use ($permissionSlug) {
      return $carry || $role->permissions->contains('slug', $permissionSlug);
    }, false);
  }

  public function givePermissionTo($permission)
  {
    $permissions = is_string($permission) ? Permission::whereName($permission)->get() : $permission;
    $this->permissions()->saveMany($permissions);
  }

  public function salesTotalCommission()
  {
    $commission = 0;

    // check if the user has sales role
    if ($this->hasRole('sales')) {
      // get all projects owned by the user
      $projects = $this->ownedProjects;

      // calculate commission for each project
      foreach ($projects as $project) {
        $commission += $project->budget * 0.1; // 10% commission
      }
    }

    return $commission;
  }

  public function avatarUrl()
  {
    return $this->avatar
      ? Storage::disk('avatars')->url($this->avatar)
      : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
  }
}
