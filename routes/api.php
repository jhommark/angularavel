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

Route::group(['prefix' => 'auth'], function () {
    // Route to register a user
    Route::post('register', ['as' => 'register', 'uses' => 'AuthController@register']);
    // Route to get a JWT via given credentials.
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    // Route to log the user out
    Route::get('logout', 'AuthController@logout');
    // Route to refresh a token
    Route::get('refresh', 'AuthController@refresh');
    // Route to get the authenticated user
    Route::get('user', 'AuthController@getAuthenticatedUser');
});
Route::group(['middleware' => ['role:admin']], function () {
    // Route to view all roles
    Route::get('roles', 'AuthController@checkRoles');
    // Route to create a new role
    Route::post('role', 'AuthController@createRole');
    // Route to create a new permission
    Route::post('permission', 'AuthController@createPermission');
    // Route to assign role to user
    Route::post('assign-role', 'AuthController@assignRole');
    // Route to attach permission to a role
    Route::post('attach-permission', 'AuthController@attachPermission');
});
