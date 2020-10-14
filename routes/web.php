<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/job', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/job/create', [App\Http\Controllers\CronjobController::class, 'create'])->name('job.create');
    Route::post('/job/create', [App\Http\Controllers\CronjobController::class, 'store'])->name('job.store');
    Route::get('/job/{id}', [App\Http\Controllers\CronjobController::class, 'show'])->name('job.show');
    Route::get('/job/{id}/edit', [App\Http\Controllers\CronjobController::class, 'edit'])->name('job.edit');
    Route::post('/job/{id}/edit', [App\Http\Controllers\CronjobController::class, 'update'])->name('job.update');
    Route::post('/job/{id}/destroy', [App\Http\Controllers\CronjobController::class, 'destroy'])->name('job.destroy');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/team/create', [App\Http\Controllers\TeamController::class, 'create'])->name('team.create');
    Route::post('/team/create', [App\Http\Controllers\TeamController::class, 'store'])->name('team.store');
    Route::get('/team/{team}', [App\Http\Controllers\TeamController::class, 'show'])->name('team.show');
    Route::get('/team/{team}/edit', [App\Http\Controllers\TeamController::class, 'edit'])->name('team.edit');
    Route::post('/team/{team}/edit', [App\Http\Controllers\TeamController::class, 'update'])->name('team.update');
    Route::delete('/team/{team}', [App\Http\Controllers\TeamController::class, 'destroy'])->name('team.delete');

    Route::get('/team/{id}/members', [App\Http\Controllers\TeamMemberController::class, 'edit'])->name('teammember.edit');
    Route::post('/team/{id}/members', [App\Http\Controllers\TeamMemberController::class, 'update'])->name('teammember.update');

    Route::get('/template', [App\Http\Controllers\TemplateController::class, 'index'])->name('template.index');
    Route::get('/template/create', [App\Http\Controllers\TemplateController::class, 'create'])->name('template.create');
    Route::post('/tamplte/create', [App\Http\Controllers\TemplateController::class, 'store'])->name('template.store');
    Route::get('/template/{template}', [App\Http\Controllers\TemplateController::class, 'show'])->name('template.show');
    Route::get('/template/{template}/edit', [App\Http\Controllers\TemplateController::class, 'edit'])->name('template.edit');
    Route::post('/template/{template}', [App\Http\Controllers\TemplateController::class, 'update'])->name('template.update');

    Route::group(['middleware' => 'admin.only', 'prefix' => 'admin'], function () {
        Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
        Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');
        Route::post('/users/create', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
        Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show');
        Route::post('/user/{id}/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
        Route::get('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
        Route::get('/job', [App\Http\Controllers\CronjobController::class, 'index'])->name('job.index');
        Route::get('/team', [App\Http\Controllers\TeamController::class, 'index'])->name('team.index');
    });
});

Route::post('/api/cronjob', [App\Http\Controllers\Api\CronjobController::class, 'update'])->name('api.cronjob.update');
