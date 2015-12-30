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

Route::get('devicepool', 'DevicePoolMigration\DevicePoolMigrationMainController@index');

//Dashboard
Route::get('/', [
    'as' => 'home',
    'uses' => 'DashboardController@index'
]);

//SQL
Route::get('sql/history', [
    'as' => 'sql.history',
    'uses' => 'SqlController@history'
]);
Route::get('sql/favorites', [
    'as' => 'sql.favorites',
    'uses' => 'SqlController@favorites'
]);
Route::resource('sql','SqlController', [
    'except' => [
        'destroy',
        'edit'
    ]
]);

//SQL Favorites
Route::resource('favorite', 'FavoriteController', [
    'only' => [
        'store',
        'destroy'
    ]
]);

//Registration
Route::resource('registration', 'RegistrationController');

//Cluster
Route::resource('cluster', 'ClusterController');

//Service Status
Route::resource('service', 'ServiceStatusController');


//AutoDialer
Route::get('autodialer/bulk', [
    'as'   => 'autodialer.bulk.index',
    'uses' => 'AutoDialerController@bulkIndex'
]);
Route::post('autodialer/bulk', [
    'as'   => 'autodialer.bulk.store',
    'uses' => 'AutoDialerController@bulkStore'
]);
Route::get('autodialer', [
    'as'   => 'autodialer.index',
    'uses' => 'AutoDialerController@index'
]);
Route::post('autodialer',[
    'as'   => 'autodialer.store',
    'uses' => 'AutoDialerController@placeCall'
]);

//CDR
Route::get('cdrs', [
    'as' => 'cdrs.index',
    'uses' => 'CdrController@index'
]);

//ITL
Route::get('itl', [
    'as'   => 'itl.index',
    'uses' => 'EraserController@itlIndex'
]);
Route::post('itl',[
    'as'   => 'itl.store',
    'uses' => 'EraserController@itlStore'
]);

//CTL
Route::get('ctl', [
    'as'   => 'ctl.index',
    'uses' => 'EraserController@ctlIndex'
]);
Route::post('ctl',[
    'as'   => 'ctl.store',
    'uses' => 'EraserController@ctlStore'
]);

// Eraser Bulk
Route::get('bulk',[
    'as'   => 'eraser.bulk.index',
    'uses' => 'EraserController@bulkIndex'
]);
Route::get('bulk/create',[
    'as'   =>  'eraser.bulk.create',
    'uses' => 'EraserController@bulkCreate'
]); 
Route::get('bulk/{bulk}',[
    'as'   =>  'eraser.bulk.show',
    'uses' => 'EraserController@bulkShow'
]);
Route::post('bulk',[
    'as'   => 'eraser.bulk.store',
    'uses' => 'EraserController@bulkStore'
]);

// Show Phone
Route::get('phone/{phone}', [
    'as'   => 'phone.show',
    'uses' => 'PhoneController@show'
]);

// Phone Firmware
Route::get('firmware',[
    'as'   => 'firmware.index',
    'uses' =>  'GetFirmwareController@index'
]);
Route::post('firmware/{devicepool}', [
    'as'   => 'firmware.store',
    'uses' => 'GetFirmwareController@store'
]);

//User
Route::resource('user', 'UserController');

//Reporting
Route::group(['prefix' => 'reports'], function() {

    //Device Reports
    Route::group(['prefix' => 'device'], function () {

        Route::get('counts', [
            'as' => 'device.counts',
            'uses' => 'ReportController@deviceCounts']);
    });
});

//Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

//Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
