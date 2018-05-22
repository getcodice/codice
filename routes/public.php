<?php

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Routes in this file will have the "web" middleware group assigned to them,
| meaning they'll support sessions, CSRF tokens etc but will NOT use the
| "auth" middleware.
|
*/

Route::get('install', 'InstallController@getWelcome');
Route::get('install/requirements', 'InstallController@getRequirements')->name('install.requirements');
Route::get('install/env', 'InstallController@getEnvironment')->name('install.environment');
Route::post('install/env', 'InstallController@postEnvironment');
Route::get('install/database/{lang}', 'InstallController@getDatabase')->name('install.database');
Route::get('install/user', 'InstallController@getUser')->name('install.user');
Route::post('install/user', 'InstallController@postUser');
Route::get('install/change-language/{lang}', 'InstallController@getChangeLanguage')->name('install.language');

Route::get('login', 'UserController@getLogin')->name('user.login')->middleware('guest');
Route::post('login', 'UserController@postLogin')->middleware('guest');

Route::get('password/email', 'UserController@getEmail')->name('password.email');
Route::post('password/email', 'UserController@postEmail');
Route::get('password/reset/{token}', 'UserController@getReset')->name('password.reset');
Route::post('password/reset', 'UserController@postReset');
