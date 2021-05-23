<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('App\Http\Controllers')->group(function(){

    Route::get('users/{id?}', 'APIController@getUsers');

    Route::post('add-users', 'APIController@addUsers');

    // POST API - Add multiple users
    Route::post('add-multiple-users', 'APIController@addMultipleUsers');

    // PUT API - Update one or more recoreds
    Route::put('update-user-details/{id}', 'APIController@updateUserDetails');

    // PATCH API - Update single record
    Route::patch('update-user-name/{id}', 'APIController@updateUserName');

    // DELETE API - Delete Single User with PARAM

    Route::delete('delete-user/{id}', 'APIController@deleteUser');

    // DELETE API Single user with json
    Route::delete('delete-user-with-json', 'APIController@deleteUserWithJson');

    // DELETE API - Delete multiple users with param
    Route::delete('delete-multiple-users/{ids}', 'APIController@deleteMultipleUsers');

    // DELETE API - Delete multiple users with json
    Route::delete('delete-multiple-users-with-json', 'APIController@deleteMultipleUsersWithJson');
});

// Route::get('users', 'App\Http\Controllers\APIController@getUsers');