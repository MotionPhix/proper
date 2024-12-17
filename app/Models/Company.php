<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
  use HasFactory, BootUuid;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'phone',
    'website',
    'address',
    'logo',
  ];

  // A company can have many contacts
  public function contacts(): HasMany
  {
    return $this->hasMany(Contact::class);
  }

  // A company can have many projects
  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }

  // A company can have many users
  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'company_user')
      ->withPivot('role')
      ->withTimestamps();
  }

  /**
   * Get the full logo URL for the company.
   *
   * @return string
   */
  public function getLogoUrlAttribute(): string
  {
    return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-logo.png');
  }

  /**
   * Check if a user is associated with the company.
   *
   * @param int $userId
   * @return bool
   */
  public function hasUser(int $userId): bool
  {
    return $this->users()->where('user_id', $userId)->exists();
  }

  /**
   * Add a user to the company with a specific role.
   *
   * @param int $userId
   * @param string $role
   * @return void
   */
  public function addUser(int $userId, string $role = 'member'): void
  {
    $this->users()->syncWithoutDetaching([
      $userId => ['role' => $role]
    ]);
  }

  /**
   * Remove a user from the company.
   *
   * @param int $userId
   * @return void
   */
  public function removeUser(int $userId): void
  {
    $this->users()->detach($userId);
  }
}
