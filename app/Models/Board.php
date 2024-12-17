<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class Board extends Model
{
  use HasFactory, BootUuid;

  protected $fillable = [
    'name',
    'project_id',
  ];

  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  public static function validationRules($project_id, $board_id = null)
  {
    return [
      'name' => [
        'required',
        'string',
        Rule::unique('boards')->where(function ($query) use ($project_id, $board_id) {
          return $query->where('project_id', $project_id)
            ->when($board_id, function ($query) use ($board_id) {
              return $query->where('id', '!=', $board_id);
            });
        })
      ],
    ];
  }
}
