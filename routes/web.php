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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'ThreadsController@index')->name('threads');
Route::get('/threads', 'ThreadsController@index')->name('threads');
Route::get('/threads/create', 'ThreadsController@create')->name('create-thread');
Route::get('/threads/{channel}', 'ThreadsController@index')->name('channel');

Route::patch('/threads/{channel}/{thread}', 'ThreadsController@update')->name('thread.update');

Route::post('/locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');

Route::delete('/locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy')->name('delete-thread');

Route::post('/threads', 'ThreadsController@store')->name('store-thread')->middleware('must-be-confirmed');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->middleware('must-be-confirmed');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('delete-reply');
Route::patch('/replies/{reply}', 'RepliesController@update')->name('update-reply');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('favorite-reply');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@delete')->name('delete-favorite-reply');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');


Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('user-profile');
Route::delete('/profiles/{user}/notifications/{notificationId}', 'UserNotificationsController@destroy');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('confirm-email');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
