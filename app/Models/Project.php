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

  /**
   * The attributes that are mass assignable.
   *
   * @var array<string, string>
   */
  protected $fillable = [
    'name',
    'description',
    'status',
    'start_date',
    'end_date',
    'company_id',
    'contact_id',
  ];

  protected $casts = [
    'start_date' => 'date:Y-m-d',
    'end_date' => 'date:j F, Y',
  ];

  public function company(): BelongsTo
  {
    return $this->belongsTo(Company::class);
  }

  public function contact(): BelongsTo
  {
    return $this->belongsTo(Contact::class);
  }

  public function boards(): HasMany
  {
    return $this->hasMany(Board::class);
  }

  public function files(): HasMany
  {
    return $this->hasMany(File::class);
  }

  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'project_user')
      ->withPivot('role')
      ->withTimestamps();
  }

  /**
   * Scope to filter projects for a specific user
   *
   * @param Builder $query
   * @param User $user
   * @return Builder
   */
  public function scopeForUser(Builder $query, $user): Builder
  {
    return $query->whereHas('users', function ($q) use ($user) {
      $q->where('users.id', $user->id);
    });
  }
}
