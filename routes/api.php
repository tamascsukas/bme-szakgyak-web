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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// List devices
Route::get('devices', 'DeviceController@index');

// Show device details
Route::get('device/{id}', 'DeviceController@show');

// Add device
Route::post('devices', 'DeviceController@store');

// Update device details
Route::put('devices', 'DeviceController@store');

// Delete device details
Route::delete('device/{id}', 'DeviceController@destroy');

// Display traffic statistics
Route::get('device/{id}/traffic-stats', 'DeviceController@showTrafficStatistics');

// Loriot API target
Route::post('loriot', 'MeasurementController@processLoriotAPIRequest');

// Seed DB
Route::get('seed-db', 'PageController@seedTables');