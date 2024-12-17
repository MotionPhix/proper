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
    Schema::create('tasks', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');

      $table->string('title', 100);
      $table->longText('description')->nullable();
      $table->enum('status', ['todo', 'in-progress', 'done'])->default('todo');
      $table->date('due_date')->nullable();
      $table->unsignedDecimal('cost', 10, 2)->default(0);

      $table->double('position')->nullable();

      $table->foreignId('board_id')->index()->constrained('boards')->onDelete('cascade');
      $table->foreignId('project_id')->index()->constrained('projects')->onDelete('cascade');
      $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

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
    Schema::dropIfExists('tasks');
  }
};
