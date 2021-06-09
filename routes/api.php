<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;

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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

//Route::post('/me', [AuthController::class, 'me']);

Route::group(['middleware' => 'auth:sanctum'], function (){

    //Users
    Route::get('/users', [UsersController::class, 'users']);
    Route::get('user/profile/{id}', [UsersController::class, 'profile']);
    Route::post('user/profile/update/{id}', [UsersController::class, 'profileUpdate']);

    //user role
    Route::get('/user/role/{user_id}', [UsersController::class, 'userRole']);
    Route::post('/user/role/assign/', [UsersController::class, 'userRoleAssign']);
    Route::get('/user/role/delete/{user_id}', [UsersController::class, 'userRoleDelete']);

    //Roles
    Route::get('/roles', [RoleController::class, 'roles']);
    Route::post('/role/store', [RoleController::class, 'store']);
    Route::get('/role/edit/{id}', [RoleController::class, 'edit']);
    Route::post('/role/update/{id}', [RoleController::class, 'update']);


});







