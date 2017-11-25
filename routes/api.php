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
    Route::get('/menu', 'MenuController@Index');

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

//    Route::post('/login', 'AuthController@Login');


});

Route::middleware(['cors'])->group(function () {//
    Route::post('/auth/login', 'AuthController@Login');
});

