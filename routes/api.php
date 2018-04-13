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

Route::get('users', 'UserController@getAllUsers');
Route::post('users', 'UserController@postInsertUsers');
Route::get('users/{id}', 'UserController@getUsersById');
Route::put('users/{id}', 'UserController@putUpdateUsersById');
Route::delete('users/{id}', 'UserController@deleteDestroyUsersById');

