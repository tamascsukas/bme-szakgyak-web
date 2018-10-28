<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/lang/{locale}', function ($locale) {
// 	App::setLocale($locale);
// 	return redirect('/');
// });

Route::get('/', 'PageController@listDevices')->name('/');
Route::get('/devices', 'PageController@listDevices')->name('devices');
Auth::routes();
Route::get('/verify/token/{token}', 'Auth\VerificationController@verify')->name('auth.verify');