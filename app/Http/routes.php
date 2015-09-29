<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Dashboard routes...
Route::get('/', 'DashboardController@index');

//SQL
Route::get('sql/history', [
    'as' => 'sql.history',
    'uses' => 'SqlController@history'
]);
Route::resource('sql','SqlController',
    ['except' => ['destroy','edit']]
);

//Registration
Route::resource('registration', 'RegistrationController');

//Cluster
Route::resource('cluster', 'ClusterController');

//Service Status
Route::resource('service', 'ServiceStatusController');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');