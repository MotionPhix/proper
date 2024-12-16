<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /*Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('roles');

    Schema::create('roles', function ($table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Schema::enableForeignKeyConstraints();*/

    $roles = [
      [
        'name' => 'Administrator',
        'description' => 'Has all permissions',
        'slug' => 'admin',
      ],

      [
        'name' => 'General Manager',
        'description' => 'Can manage projects',
        'slug' => 'general-manager',
      ],

      [
        'name' => 'Sales Personnel',
        'description' => 'Can view projects and tasks',
        'slug' => 'sales-member'
      ],

      [
        'name' => 'Graphic Designer',
        'description' => 'Can view projects and tasks',
        'slug' => 'design-member'
      ],

      [
        'name' => 'Printing Production',
        'description' => 'Can view projects and tasks',
        'slug' => 'production-member'
      ]
    ];

    foreach ($roles as $role) {
      \App\Models\Role::create($role);
    }

    $permissions = \App\Models\Permission::pluck('id');
    $unSyncedRoles = \App\Models\Role::get();

    $unSyncedRoles->each(function($role) use($permissions) {
      $role->syncPermissions([$permissions->random()]);
    });
  }
}
