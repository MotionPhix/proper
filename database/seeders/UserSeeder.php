<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /*Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('users');

    Schema::create('users', function ($table) {
      $table->id();
      $table->string('name');
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::enableForeignKeyConstraints();*/

    \App\Models\User::factory()->create(['email' => 'hello@ultrashots.net']);
    \App\Models\User::factory(2)->create();

    $users = \App\Models\User::all();
  }
}
