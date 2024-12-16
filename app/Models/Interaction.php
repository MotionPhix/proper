<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interaction extends Model
{
  use HasFactory, BootUuid;

  public function contact(): BelongsTo
  {
    return $this->belongsTo(Contact::class);
  }

  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
