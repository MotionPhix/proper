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

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'status',
    'position',
    'company_id',
    'user_id',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  // A contact belongs to a company
  public function company(): BelongsTo
  {
    return $this->belongsTo(Company::class);
  }

  // A contact may own multiple projects
  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }

  // A contact may be linked to multiple users
  public function users(): HasMany
  {
    return $this->hasMany(User::class);
  }

  /**
   * Get the full name of the contact.
   *
   * @return string
   */
  public function getFullNameAttribute(): string
  {
    return "{$this->first_name} {$this->last_name}";
  }

  public function phones()
  {
    return $this->morphMany(Phone::class, 'model');
  }

  /**
   * Custom Methods
   */

  /**
   * Check if the contact belongs to a given company
   *
   * @param int $companyId
   * @return bool
   */
  public function belongsToCompany(int $companyId): bool
  {
    return $this->company_id === $companyId;
  }
}
