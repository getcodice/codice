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

Route::get('/', ['as' => 'index', 'uses' => 'NoteController@getIndex']);

Route::get('about', ['as' => 'about', 'uses' => 'InfoController@getAbout']);
Route::get('about/check-updates', ['as' => 'about.updates', 'uses' => 'InfoController@getUpdates']);

Route::get('calendar', ['as' => 'calendar', 'uses' => 'CalendarController@getIndex']);
Route::get('calendar/{year}-{month}', ['as' => 'calendar.month', 'uses' => 'CalendarController@getMonth'])
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);
Route::get('calendar/{year}-{month}-{day}', ['as' => 'calendar.day', 'uses' => 'CalendarController@getDay'])
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}', 'day' => '[0-9]{2}']);

Route::get('create', ['as' => 'note.create', 'uses' => 'NoteController@getCreate']);
Route::post('create', 'NoteController@postCreate');

Route::get('install', 'InstallController@getWelcome');
Route::get('install/requirements', ['as' => 'install.requirements', 'uses' => 'InstallController@getRequirements']);
Route::get('install/env', ['as' => 'install.environment', 'uses' => 'InstallController@getEnvironment']);
Route::post('install/env', 'InstallController@postEnvironment');
Route::get('install/database/{lang}', ['as' => 'install.database', 'uses' => 'InstallController@getDatabase']);
Route::get('install/user', ['as' => 'install.user', 'uses' => 'InstallController@getUser']);
Route::post('install/user', 'InstallController@postUser');
Route::get('install/final', ['as' => 'install.final', 'uses' => 'InstallController@getFinal']);
Route::get('install/change-language/{lang}', ['as' => 'install.language', 'uses' => 'InstallController@getChangeLanguage']);

Route::get('login', ['as' => 'user.login', 'uses' => 'UserController@getLogin']);
Route::post('login', 'UserController@postLogin');
Route::get('logout', ['as' => 'user.logout', 'uses' => 'UserController@getLogout']);

Route::get('note/{id}', ['as' => 'note', 'uses' => 'NoteController@getNote']);
Route::get('note/{id}/edit', ['as' => 'note.edit', 'uses' => 'NoteController@getEdit']);
Route::post('note/{id}/edit', 'NoteController@postEdit');
Route::get('note/{id}/mark', ['as' => 'note.change', 'uses' => 'NoteController@getChangeStatus']);
Route::get('note/{id}/remove', ['as' => 'note.remove', 'uses' => 'NoteController@getRemove']);

Route::get('labels', ['as' => 'labels', 'uses' => 'LabelController@getIndex']);
Route::get('labels/create', ['as' => 'label.create', 'uses' => 'LabelController@getCreate']);
Route::post('labels/create', 'LabelController@postCreate');
Route::get('label/{id}', ['as' => 'label', 'uses' => 'LabelController@getNotes']);
Route::get('label/{id}/edit', ['as' => 'label.edit', 'uses' => 'LabelController@getEdit']);
Route::post('label/{id}/edit', 'LabelController@postEdit');
Route::get('label/{id}/remove', ['as' => 'label.remove', 'uses' => 'LabelController@getRemove']);

/*
Route::get('plugin/{id}/disable', ['as' => 'plugin.disable', 'uses' => 'PluginController@getDisable']);
Route::get('plugin/{id}/enable', ['as' => 'plugin.enable', 'uses' => 'PluginController@getEnable']);
Route::get('plugin/{id}/install', ['as' => 'plugin.install', 'uses' => 'PluginController@getInstall']);
Route::get('plugin/{id}/remove', ['as' => 'plugin.remove', 'uses' => 'PluginController@getRemove']);
Route::get('plugin/{id}/uninstall', ['as' => 'plugin.uninstall', 'uses' => 'PluginController@getUninstall']);
Route::get('plugins', ['as' => 'plugins', 'uses' => 'PluginController@getIndex']);
*/

Route::get('reminders', ['as' => 'reminders', 'uses' => 'ReminderController@getIndex']);
Route::get('reminder/{id}/remove', ['as' => 'reminder.remove', 'uses' => 'ReminderController@getRemove']);

Route::get('settings', ['as' => 'settings', 'uses' => 'UserController@getSettings']);
Route::post('settings', 'UserController@postSettings');
Route::get('settings/insert-welcome-note', ['as' => 'settings.welcome-note', 'uses' => 'UserController@getInsertWelcomeNote']);

Route::get('stats', ['as' => 'stats', 'uses' => 'InfoController@getStats']);
