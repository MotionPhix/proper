<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Phone extends Model
{
  use HasFactory, BootUuid;

  protected $table = 'phone_numbers';

  public function phoneable(): MorphTo
  {
    return $this->morphTo();
  }
}
