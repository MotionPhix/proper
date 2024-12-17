<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $permissions = [
      // Projects Permissions
      ['name' => 'create project', 'slug' => 'create-project', 'description' => 'Allows user to create a new project.'],
      ['name' => 'read projects', 'slug' => 'read-projects', 'description' => 'Allows user to read all projects.'],
      ['name' => 'read project', 'slug' => 'read-project', 'description' => 'Allows user to read a specific project.'],
      ['name' => 'delete project', 'slug' => 'delete-project', 'description' => 'Allows user to delete a specific project.'],
      ['name' => 'edit project', 'slug' => 'update-project', 'description' => 'Allows user to update a specific project.'],
      ['name' => 'assign project', 'slug' => 'assign-project', 'description' => 'Allows user to transfer project ownership.'],

      // Board Permissions
      ['name' => 'create board', 'slug' => 'create-board', 'description' => 'Allows user to create a new board.'],
      ['name' => 'read boards', 'slug' => 'read-boards', 'description' => 'Allows user to read all boards.'],
      ['name' => 'read board', 'slug' => 'read-board', 'description' => 'Allows user to read a specific board.'],
      ['name' => 'delete board', 'slug' => 'delete-board', 'description' => 'Allows user to delete a specific board.'],
      ['name' => 'edit board', 'slug' => 'update-board', 'description' => 'Allows user to update a specific board.'],

      // Task Permissions
      ['name' => 'create task', 'slug' => 'create-task', 'description' => 'Allows user to create new tasks.'],
      ['name' => 'read tasks', 'slug' => 'read-tasks', 'description' => 'Allows user to read all tasks.'],
      ['name' => 'read task', 'slug' => 'read-task', 'description' => 'Allows user to read a specific task.'],
      ['name' => 'delete task', 'slug' => 'delete-task', 'description' => 'Allows user to delete a specific task.'],
      ['name' => 'edit task', 'slug' => 'update-task', 'description' => 'Allows user to update a specific task.'],
      ['name' => 'assign task', 'slug' => 'assign-task', 'description' => 'Allows user to assign a task.'],
      ['name' => 'move task', 'slug' => 'move-task', 'description' => 'Allows user to move a task between boards.'],


      // Contact Permissions
      ['name' => 'create contact', 'slug' => 'create-contact', 'description' => 'Allows user to create new contacts.'],
      ['name' => 'read contacts', 'slug' => 'read-contacts', 'description' => 'Allows user to read all contacts.'],
      ['name' => 'read contact', 'slug' => 'read-contact', 'description' => 'Allows user to read a specific contact.'],
      ['name' => 'delete contact', 'slug' => 'delete-contact', 'description' => 'Allows user to delete a specific contact.'],
      ['name' => 'edit contact', 'slug' => 'update-contact', 'description' => 'Allows user to update a specific contact.'],
      ['name' => 'assign contact', 'slug' => 'assign-contact', 'description' => 'Allows user to assign a contact to a user.'],

      // Company Permissions
      ['name' => 'create company', 'slug' => 'create-company', 'description' => 'Allows user to create new companies.'],
      ['name' => 'read companies', 'slug' => 'read-companies', 'description' => 'Allows user to read all companies.'],
      ['name' => 'read company', 'slug' => 'read-company', 'description' => 'Allows user to read a specific company.'],
      ['name' => 'delete company', 'slug' => 'delete-company', 'description' => 'Allows user to delete a specific company.'],
      ['name' => 'edit company', 'slug' => 'update-company', 'description' => 'Allows user to update a specific company.'],
      ['name' => 'assign company', 'slug' => 'assign-company', 'description' => 'Allows user to assign a company to a user.'],

        // Users Permissions
      ['name' => 'create user', 'slug' => 'create-user', 'description' => 'Allows user to create new users.'],
      ['name' => 'read users', 'slug' => 'read-users', 'description' => 'Allows user to read all users.'],
      ['name' => 'read user', 'slug' => 'read-user', 'description' => 'Allows user to read a specific user.'],
      ['name' => 'delete user', 'slug' => 'delete-user', 'description' => 'Allows user to delete a specific user.'],
      ['name' => 'edit user', 'slug' => 'update-user', 'description' => 'Allows user to update a specific user.'],
    ];

    // Create permissions
    foreach ($permissions as $permission) {
      Permission::create(['name' => $permission['name']]);
    }

    // Admin role
    $admin = Role::create(['name' => 'admin']);
    $admin->givePermissionTo(Permission::all());

    // Director role
    $director = Role::create(['name' => 'director']);
    $director->givePermissionTo([
      'create project',
      'read projects',
      'read project',
      'edit project',
      'create board',
      'read boards',
      'read board',
      'edit board',
      'create task',
      'read tasks',
      'read task',
      'edit task',
      'create contact',
      'read contacts',
      'read contact',
      'edit contact',
      'create company',
      'read companies',
      'read company',
      'edit company',
      'read users',
      'edit user',
      'delete user',
      'read user'
    ]);

    // Manager role
    $manager = Role::create(['name' => 'manager']);
    $manager->givePermissionTo([
      'read projects',
      'create project',
      'edit project',
      'read task',
      'create task',
      'edit task',
    ]);

    // Sales role
    $sales = Role::create(['name' => 'sales']);
    $sales->givePermissionTo([
      'read projects',
      'read project',
      'create project',
      'create contact',
      'delete contact',
      'edit contact',
      'read contacts',
      'read contact',
      'create company',
      'delete company',
      'edit company',
      'read company',
      'read company',
      'create task',
      'delete task',
      'edit task',
      'read tasks',
    ]);

    // Designer role
    $designer = Role::create(['name' => 'designer']);
    $designer->givePermissionTo([
      'read projects',
      'read project',
      'create task',
      'edit task',
      'delete task',
      'read task',
      'read tasks',
      'read boards',
      'read board',
      'edit board',
      'delete board'
    ]);

    // Accounts role
    $accounts = Role::create(['name' => 'accounts']);
    $accounts->givePermissionTo([
      'read projects',
      'read project',
      'read tasks',
      'read users',
    ]);
  }
}
