<?php

namespace App\Models;

use App\Traits\BootUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
  use HasFactory, BootUuid;

  protected $fillable = [
    'title',
    'description',
    'status',
    'due_date',
    'board_id',
    'project_id',
    'assigned_to',
  ];

  const STATUSES = [
    'new' => 'New',
    'in_progress' => 'In Progress',
    'cancelled' => 'Cancelled',
    'done' => 'Completed'
  ];

  const POSITION_GAP = 60000;

  const POSITION_MIN =  0.00002;

  /**
   * Relationships
   */
  public function board(): BelongsTo
  {
    return $this->belongsTo(Board::class);
  }

  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  public function assignedUser(): BelongsTo
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  public static function booted()
  {
    static::creating(function ($model) {
      $model->position = self::query()->where('board_id', $model->board_id)->orderByDesc('position')->first()?->position + self::POSITION_GAP;
    });

    static::saved(function ($model) {
      if ($model->position < self::POSITION_MIN) {
        \DB::statement("SET @previousPosition := 0");
        \DB::statement("
          UPDATE tasks
          SET position = (@previousPosition := @previousPosition + ?)
          WHERE board_id = ?
          ORDER BY position
        ", [
          self::POSITION_GAP,
          $model->board_id
        ]);
      }
    });
  }
}
