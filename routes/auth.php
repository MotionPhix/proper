<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::get(
    'register',
    [RegisteredUserController::class, 'create']
  )->name('register');

  Route::post(
    'register',
    [RegisteredUserController::class, 'store']
  );

  Route::get(
    'login',
    [AuthenticatedSessionController::class, 'create']
  )->name('login');

  Route::post(
    'login',
    [AuthenticatedSessionController::class, 'store']
  );

  Route::get(
    'forgot-password',
    [PasswordResetLinkController::class, 'create']
  )->name('password.request');

  Route::post(
    'forgot-password',
    [PasswordResetLinkController::class, 'store']
  )->name('password.email');

  Route::get(
    'reset-password/{token}',
    [NewPasswordController::class, 'create']
  )->name('password.reset');

  Route::post(
    'reset-password',
    [NewPasswordController::class, 'store']
  )->name('password.store');
});

Route::middleware('auth')->group(function () {
  Route::get(
    'verify-email',
    EmailVerificationPromptController::class
  )->name('verification.notice');

  Route::get(
    'verify-email/{id}/{hash}',
    VerifyEmailController::class
  )->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

  Route::post(
    'email/verification-notification',
    [EmailVerificationNotificationController::class, 'store']
  )->middleware('throttle:6,1')
    ->name('verification.send');

  Route::get(
    'confirm-password',
    [ConfirmablePasswordController::class, 'show']
  )->name('password.confirm');

  Route::post(
    'confirm-password',
    [ConfirmablePasswordController::class, 'store']
  );

  Route::put(
    'password',
    [PasswordController::class, 'update']
  )->name('password.update');

  Route::post(
    'logout',
    [AuthenticatedSessionController::class, 'destroy']
  )->name('logout');

  // projets
  Route::prefix('projects')->group(function () {

    Route::get(
      '/',
      \App\Http\Controllers\Project\Index::class,
    )->name('projects.index');

    Route::get(
      '/c',
      \App\Http\Controllers\Project\Form::class
    )->name('projects.create');

    Route::get(
      '/t/{project:uuid}',
      \App\Http\Controllers\Project\Transfer::class,
    )->name('projects.show-transfer');

    Route::post(
      '/',
      \App\Http\Controllers\Project\Store::class,
    )->name('projects.store');

    Route::post(
      '/t',
      \App\Http\Controllers\Project\StoreTransfer::class,
    )->name('projects.store-transfer');

    Route::get(
      '/a/{project:uuid}',
      \App\Http\Controllers\Project\Member::class,
    )->name('projects.show-member');

    Route::post(
      '/a',
      \App\Http\Controllers\Project\StoreMember::class,
    )->name('projects.store-member');

    Route::get(
      '/e/{project:uuid}',
      \App\Http\Controllers\Project\Form::class,
    )->name('projects.edit');

    Route::patch(
      '/u/{project:uuid}/{user?}',
      \App\Http\Controllers\Project\Update::class,
    )->name('projects.update');

    Route::put(
      '/r/{project:uuid}',
      \App\Http\Controllers\Project\Rename::class,
    )->name('projects.rename');

    Route::get(
      '/s/{project:uuid}',
      \App\Http\Controllers\Project\Show::class
    )->name('projects.show');

    Route::delete(
      '/{project:uuid}',
      \App\Http\Controllers\Project\Destroy::class,
    )->name('projects.destroy');

  });

  Route::get(
    '/',
    \App\Http\Controllers\Analytics\Index::class,
  )->name('dashboard');

  // contacts
  // Route::get('/projects/{project}/transfer', \App\Actions\OpenTransferProject::class)->name('projects.show-transfer');
  // Route::get('/projects/{project}/add', \App\Actions\OpenAddMember::class)->name('projects.openaddmember');
  Route::middleware(['is_authorised'])->group(function () {
    Route::get('/contacts/create', \App\Actions\FormContact::class)->name('contacts.create');
    Route::post('/contacts', \App\Actions\CreateContact::class)->name('contacts.store');
    Route::delete('/contacts/{contact}', \App\Actions\DeleteContact::class)->name('contacts.destroy');
    // Route::post('/projects/transfer', \App\Actions\TransferProject::class)->name('projects.transfer');
    // Route::post('/projects/addmember', \App\Actions\AddMember::class)->name('projects.addmember');
    Route::get('/contacts/{contact}/edit', \App\Actions\FormContact::class)->name('contacts.edit');
    Route::patch('/contacts/{contact}', \App\Actions\UpdateContact::class)->name('contacts.update');
    Route::get('/contacts/index/{modal?}', \App\Actions\Contacts\LoadContacts::class)->name('contacts.index');
    Route::get('/contacts/show/{contact}/{modal?}', \App\Actions\ShowContact::class)->name('contacts.show');
  });

  // boards
  // Route::get('/boards', \App\Actions\ListTasks::class)->name('tasks.index');
  // Route::get('/boards/{project}/create', \App\Actions\FormTask::class)->name('tasks.create');
  Route::post('/boards/{project}', \App\Actions\CreateBoard::class)->name('boards.store');
  // Route::get('/boards/{board}', \App\Actions\ShowTask::class)->name('tasks.show');
  Route::patch('/boards/{board}', \App\Actions\UpdateBoard::class)->name('boards.update');
  Route::delete('/boards/{board}', \App\Actions\DeleteBoard::class)->name('boards.destroy');

  // tasks
  Route::get('/tasks', \App\Actions\ListTasks::class)->name('tasks.index');
  Route::get('/tasks/{project}/create', \App\Actions\FormTask::class)->name('tasks.create');
  Route::post('/tasks', \App\Actions\CreateTask::class)->name('tasks.store');
  Route::get('/tasks/{task}', \App\Actions\ShowTask::class)->name('tasks.show');
  Route::patch('/tasks/{task}', \App\Actions\UpdateTask::class)->name('tasks.update');
  Route::delete('/tasks/{task}', \App\Actions\DeleteTask::class)->name('tasks.destroy');
  Route::put('/tasks/{task}', \App\Actions\MoveTask::class)->name('tasks.move');

  // users
  Route::get('/users', \App\Actions\Users\LoadUsers::class)->name('users.index');

  // reports
  Route::get('/reports', \App\Actions\Reports\LoadReport::class)->name('reports.index');

  // files
  Route::get('/files/{project}', \App\Actions\FormFile::class)->name('files.load');
  Route::delete('/files/{file}', \App\Actions\DeleteFile::class)->name('files.destroy');
  Route::post('/files/{project}', \App\Actions\UploadFile::class)->name('files.upload');

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // companies
  Route::get('/companies', \App\Actions\Companies\LoadCompanies::class)->name('companies.index');
  Route::get('/companies/{company}/contacts', \App\Actions\Companies\LoadCompanyContacts::class)->name('companies.contacts');
  // Route::get('/contacts', \App\Actions\ListContacts::class)->name('contacts.index');
  // Route::get('/contacts/create', \App\Actions\FormContact::class)->name('contacts.create');
  // Route::post('/contacts', \App\Actions\CreateContact::class)->name('contacts.store');
  // Route::delete('/contacts/{contact}', \App\Actions\DeleteContact::class)->name('contacts.destroy');
  // Route::post('/projects/transfer', \App\Actions\TransferProject::class)->name('projects.transfer');
  // Route::post('/projects/addmember', \App\Actions\AddMember::class)->name('projects.addmember');
  // Route::get('/contacts/{contact}/edit', \App\Actions\FormContact::class)->name('contacts.edit');
  // Route::patch('/contacts/{contact}', \App\Actions\UpdateContact::class)->name('contacts.update');
  // Route::get('/contacts/{contact?}/{modal?}', \App\Actions\ShowContact::class)->name('contacts.show');

  // load users to assign a project
  Route::get('/assignable/{project}', \App\Actions\AssignableUsers::class)->name('assignable.users');

});
