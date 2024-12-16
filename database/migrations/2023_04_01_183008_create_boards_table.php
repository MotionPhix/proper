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

      $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->string('name');
      $table->timestamps();

      $table->unique(['name', 'project_id']);
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