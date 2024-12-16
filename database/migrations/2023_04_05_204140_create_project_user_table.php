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
    Schema::create('project_user', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('project_id');
      $table->unsignedBigInteger('assigned_by')->nullable();
      $table->string('role', 20)->nullable();
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
    Schema::dropIfExists('project_user');
  }
};
