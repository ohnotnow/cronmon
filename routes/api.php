<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/ping/{uuid}', 'ApiController@ping')->name('ping.get');
Route::post('/ping/{uuid}', 'ApiController@ping')->name('ping.post');
Route::post('/api/templates/{slug}', 'Api\TemplateController@store')->name('api.template.create_job');

Route::get('/api/cronjob/{uuid}', 'Api\CronjobController@show')->name('api.cronjob.show');

// POST job  -- create a new job - returns json of the job
// POST job/{uuid}  -- update a job - returns json of the job
// POST job/{uuid}/silence -- silence a job
// POST job/{uuid}/unsilence -- unsilence a job
// GET job/{uuid}?token={token} -- return json of specific job
// DELETE job/{uuid}?token={token} -- delete a given job

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');
