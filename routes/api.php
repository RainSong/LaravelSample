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


Route::middleware(['cors', 'api', 'JWTAuth'])->group(function () {//

    Route::get('/menu','MenuController@Index');

    Route::get('/module', 'ModuleController@Index');
    Route::get('/module/{id}','ModuleController@Show');
    Route::post('/module','ModuleController@Add');
    Route::patch('/module','ModuleController@Update');
    Route::delete('/module','ModuleController@Delete');

    Route::get('/interview', 'InterviewController@Index');
    Route::get('/interview/{id}', 'InterviewController@Show');
    Route::post('/interview', 'InterviewController@Add');
    Route::patch('interview', 'InterviewController@Update');
    Route::delete('/interview/{id}', 'InterviewController@Delete');

    Route::get('/department', 'DepartmentController@Index');
    Route::get('/department/{id}', 'DepartmentController@Show');
    Route::post('/department', 'DepartmentController@Add');
    Route::patch('/department', 'DepartmentController@Update');
    Route::delete('/department/{id}', 'DepartmentController@Delete');

    Route::get('/user', 'UserController@Index');
    Route::get('/user/{id}', 'UserController@Show');
    Route::post('/user', 'UserController@Add');
    Route::patch('/user', 'UserController@Update');
    Route::delete('/user', 'UserController@Delete');
    Route::post('/user/resetpassword', 'UserController@ResetPassword');

    Route::get('/role', 'RoleController@Index');
    Route::get('/role/{id}', 'RoleController@Show');
    Route::post('/role', 'RoleController@Add');
    Route::patch('/role', 'RoleController@Update');
    Route::delete('/role', 'RoleController@Delete');

    Route::get('/permissions', 'PermissionsController@Index');
    Route::get('/permissions/{id}', 'PermissionsController@Show');
    Route::post('/permissions', 'PermissionsController@Add');
    Route::patch('/permissions', 'PermissionsController@Update');
    Route::delete('/permissions', 'PermissionsController@Delete');

    Route::get('/user/role/{id}', 'UserRoleController@Index');
    Route::post('/user/role', 'UserRoleController@Update');

    Route::get('/role/permissions/{id}','RolePermissionsController@Index');
    Route::post('/role/permissions','RolePermissionsController@Update'); 

    Route::get('/permissions/module/{id}','PermissionsModuleController@Index');
    Route::post('/permissions/module','PermissionsModuleController@Update');

    Route::get('/attendance','AttendanceController@Index');
    Route::get('/attendance/{id}','AttendanceController@Show');
    Route::post('/attendance','AttendanceController@Add');
    Route::patch('/attendance','AttendanceController@Update');
    Route::delete('/attendance', 'AttendanceController@Delete');
});

Route::middleware(['cors'])->group(function () {//
    Route::post('/auth/login', 'AuthController@Login');
});

