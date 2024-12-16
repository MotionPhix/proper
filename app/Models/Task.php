<?php

namespace App\Models;

use App\Traits\BootUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  use HasFactory, BootUuid;

  const STATUSES = [
    'new' => 'New',
    'in_progress' => 'In Progress',
    'cancelled' => 'Cancelled',
    'done' => 'Completed'
  ];

  const POSITION_GAP = 60000;

  const POSITION_MIN =  0.00002;

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function board()
  {
    return $this->belongsTo(Board::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function humanCost(): Attribute
  {
    return Attribute::make(
      get: function () {
        return 'MK ' . number_format($this->cost / 100, 2);
      },
    );
  }

  public function getStatusColorAttribute()
  {
    return [
      'done' => 'green',
      'cancelled' => 'red',
      'in_progress' => 'blue'
    ][$this->status] ?? 'gray';
  }

  public function getStatusDisplayAttribute()
  {
    return [
      'done' => 'Completed',
      'cancelled' => 'Cancelled',
      'in_progress' => 'In Progress'
    ][$this->status] ?? 'New';
  }

  public function setStartDateAttribute($value)
  {
    $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d H:i');
  }

  public function setEndDateAttribute($value)
  {
    $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d H:i');
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
