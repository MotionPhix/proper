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
    Schema::create('contacts', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');

      $table->string('first_name', 30);
      $table->string('last_name', 30);
      $table->string('position', 50)->nullable();
      $table->string('email', 50)->nullable();
      $table->enum('status', ['active', 'in-active'])->default('active');

      $table->foreignId('company_id')->index()->constrained('companies')->onDelete('cascade');
      $table->foreignId('user_id')->index()->constrained('users')->onDelete('cascade');

      $table->unique(['first_name', 'last_name', 'email', 'company_id']);

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
    Schema::dropIfExists('contacts');
  }
};
