<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
  use HasFactory, BootUuid;

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function getHumanReadableSize(): string
  {
    $sizeInBytes = Storage::size($this->path);
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $index = 0;

    while ($sizeInBytes >= 1024 && $index < count($units) - 1) {
      $sizeInBytes /= 1024;
      $index++;
    }

    return round($sizeInBytes, 2) . ' ' . $units[$index];
  }

  public function icon()
  {
    switch (pathinfo($this->original_filename, PATHINFO_EXTENSION)) {
      case 'pdf':
        return 'carbon-document-pdf';
      case 'jpg':
      case 'jpeg':
      case 'png':
        return 'phosphor-file-image';
      case 'doc':
      case 'docx':
        return 'phosphor-microsoft-word-logo';
      default:
        return 'phosphor-file';
    }
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function getFullPath(): string
  {
    return Storage::disk('documents')->path($this->path);
  }
}
