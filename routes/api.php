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
// Route to create a new user
Route::post('auth/register', 'AuthController@register');
// Route to authenticate a user
Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function () {
	// Route to get the authenticated user
	Route::get('user', 'AuthController@getAuthenticatedUser');
});
