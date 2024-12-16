<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class InteractionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /*Schema::disableForeignKeyConstraints();

    Schema::dropIfExists('interactions');

    Schema::create('interactions', function ($table) {
      $table->id();

      $table->string('type');
      $table->text('description')->nullable();
      $table->timestamps();

      $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

      $table->index(['contact_id', 'created_at']);
    });

    Schema::enableForeignKeyConstraints();*/

    $projects = \App\Models\Project::whereHas('owner')->get();

    $projects->each(function ($project) {
      $interaction = \App\Models\Interaction::factory()->make([
        'contact_id' => $project->contact_id,
        'project_id' => $project->id,
        'user_id' => $project->owner->id
      ]);

      $interaction->save();
    });
  }
}
