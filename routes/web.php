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

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/job', 'HomeController@index');
    Route::get('/job/create', 'CronjobController@create')->name('job.create');
    Route::post('/job/create', 'CronjobController@store')->name('job.store');
    Route::get('/job/{id}', 'CronjobController@show')->name('job.show');
    Route::get('/job/{id}/edit', 'CronjobController@edit')->name('job.edit');
    Route::post('/job/{id}/edit', 'CronjobController@update')->name('job.update');
    Route::post('/job/{id}/destroy', 'CronjobController@destroy')->name('job.destroy');

    Route::get('/profile', 'ProfileController@show')->name('profile.show');
    Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/edit', 'ProfileController@update')->name('profile.update');

    Route::get('/team/create', 'TeamController@create')->name('team.create');
    Route::post('/team/create', 'TeamController@store')->name('team.store');
    Route::get('/team/{team}', 'TeamController@show')->name('team.show');
    Route::get('/team/{team}/edit', 'TeamController@edit')->name('team.edit');
    Route::post('/team/{team}/edit', 'TeamController@update')->name('team.update');

    Route::get('/team/{id}/members', 'TeamMemberController@edit')->name('teammember.edit');
    Route::post('/team/{id}/members', 'TeamMemberController@update')->name('teammember.update');

    Route::group(['middleware' => 'admin.only', 'prefix' => 'admin'], function () {
        Route::get('/users', 'UserController@index')->name('user.index');
        Route::get('/users/create', 'UserController@create')->name('user.create');
        Route::post('/users/create', 'UserController@store')->name('user.store');
        Route::get('/user/{id}', 'UserController@show')->name('user.show');
        Route::post('/user/{id}/destroy', 'UserController@destroy')->name('user.destroy');
        Route::get('/user/{id}/edit', 'UserController@edit')->name('user.edit');
        Route::post('/user/{id}/edit', 'UserController@update')->name('user.update');
        Route::get('/job', 'CronjobController@index')->name('job.index');
        Route::get('/team', 'TeamController@index')->name('team.index');
    });
});
