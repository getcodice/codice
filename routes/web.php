<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'NoteController@getIndex')->name('index');

Route::get('about', 'InfoController@getAbout')->name('about');
Route::get('about/check-updates', 'InfoController@getUpdates')->name('about.updates');

Route::get('calendar', 'CalendarController@getIndex')->name('calendar');
Route::get('calendar/{year}-{month}', 'CalendarController@getMonth')->name('calendar.month')
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);
Route::get('calendar/{year}-{month}-{day}', 'CalendarController@getDay')->name('calendar.day')
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}', 'day' => '[0-9]{2}']);

Route::get('create', 'NoteController@getCreate')->name('note.create');
Route::post('create', 'NoteController@postCreate');

Route::get('install', 'InstallController@getWelcome');
Route::get('install/requirements', 'InstallController@getRequirements')->name('install.requirements');
Route::get('install/env', 'InstallController@getEnvironment')->name('install.environment');
Route::post('install/env', 'InstallController@postEnvironment');
Route::get('install/database/{lang}', 'InstallController@getDatabase')->name('install.database');
Route::get('install/user', 'InstallController@getUser')->name('install.user');
Route::post('install/user', 'InstallController@postUser');
Route::get('install/change-language/{lang}', 'InstallController@getChangeLanguage')->name('install.language');

Route::get('login', 'UserController@getLogin')->name('user.login');
Route::post('login', 'UserController@postLogin');
Route::get('logout', 'UserController@getLogout')->name('user.logout');

Route::get('note/{id}', 'NoteController@getNote')->name('note');
Route::get('note/{id}/edit', 'NoteController@getEdit')->name('note.edit');
Route::post('note/{id}/edit', 'NoteController@postEdit');
Route::get('note/{id}/remove', 'NoteController@getRemove')->name('note.remove');
Route::get('note/{id}/remove/confirm', 'NoteController@getRemoveConfirm')->name('note.remove.confirm');
Route::get('note/{id}/toggle', 'NoteController@getToggle')->name('note.toggle');

Route::get('labels', 'LabelController@getIndex')->name('labels');
Route::get('labels/create', 'LabelController@getCreate')->name('label.create');
Route::post('labels/create', 'LabelController@postCreate');
Route::get('label/{id}', 'LabelController@getNotes')->name('label');
Route::get('label/{id}/edit', 'LabelController@getEdit')->name('label.edit');
Route::post('label/{id}/edit', 'LabelController@postEdit');
Route::get('label/{id}/remove', 'LabelController@getRemove')->name('label.remove');

Route::get('plugins', 'PluginController@getIndex')->name('plugins');
Route::get('plugin/{id}/disable', 'PluginController@getDisable')->name('plugin.disable');
Route::get('plugin/{id}/enable', 'PluginController@getEnable')->name('plugin.enable');
Route::get('plugin/{id}/install', 'PluginController@getInstall')->name('plugin.install');
Route::get('plugin/{id}/remove', 'PluginController@getRemove')->name('plugin.remove');
Route::get('plugin/{id}/uninstall', 'PluginController@getUninstall')->name('plugin.uninstall');

Route::get('password/email', 'UserController@getEmail')->name('password.email');
Route::post('password/email', 'UserController@postEmail');
Route::get('password/reset/{token}', 'UserController@getReset')->name('password.reset');
Route::post('password/reset', 'UserController@postReset');

Route::get('reminders', 'ReminderController@getIndex')->name('reminders');
Route::get('reminder/{id}/remove', 'ReminderController@getRemove')->name('reminder.remove');

Route::get('search', 'SearchController@getIndex')->name('search');

Route::get('settings', 'UserController@getSettings')->name('settings');
Route::post('settings', 'UserController@postSettings');

Route::get('stats', 'InfoController@getStats')->name('stats');
