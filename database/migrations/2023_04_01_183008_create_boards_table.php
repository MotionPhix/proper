<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('boards', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');

      $table->string('name');
      $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
      $table->foreignId('project_id')->index()->constrained('projects')->onDelete('cascade');

      $table->unique(['name', 'project_id']);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('boards');
  }
};
