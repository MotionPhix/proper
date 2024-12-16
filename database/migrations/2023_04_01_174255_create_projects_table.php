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
    Schema::create('projects', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');

      $table->string('name', 50);
      $table->text('description')->nullable();
      $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');

      $table->foreignId('company_id')->index()->constrained('companies')->onDelete('cascade');
      $table->foreignId('contact_id')->index()->constrained('contacts')->onDelete('cascade');

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
    Schema::dropIfExists('projects');
  }
};
