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
    Schema::create('phone_numbers', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->string('number');
      $table->enum('type', ['mobile', 'work', 'home', 'fax'])->default('mobile');
      $table->morphs('model');
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
    Schema::dropIfExists('phone_numbers');
  }
};
