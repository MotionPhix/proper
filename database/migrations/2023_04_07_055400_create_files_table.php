<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('files', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');

      $table->string('original_filename', 70);
      $table->string('path');

      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->foreignId('user_id')->constrained('users');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('files');
  }
};
