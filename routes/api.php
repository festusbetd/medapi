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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'ApiController@logout');
    Route::get('user', 'ApiController@getAuthUser');
    Route::get('admin/patients', 'PatientController@index');
    
    Route::get('reception/patients', 'PatientController@reception');
    Route::get('nurse/patients', 'PatientController@nurse');
    Route::get('doctor/patients', 'PatientController@doctor');

    Route::get('patients/{id}', 'PatientController@show');
    Route::post('reception/patients', 'PatientController@store');
    Route::put('patients/{id}', 'PatientController@update');
    Route::delete('patients/{id}', 'PatientController@destroy');
});