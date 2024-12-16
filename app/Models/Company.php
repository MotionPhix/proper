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

  public function contacts()
  {
    return $this->hasMany(Contact::class);
  }

  public function projects()
  {
    return $this->hasMany(Project::class);
  }

  public function phoneNumbers()
  {
    return $this->morphMany(PhoneNumber::class, 'phoneable');
  }

  public function scopeOrderByName(Builder $query)
  {
    return $query->orderBy('name');
  }
}
