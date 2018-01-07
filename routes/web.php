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
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy')->name('delete-thread');

Route::post('/threads', 'ThreadsController@store')->name('store-thread');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('delete-reply');
Route::patch('/replies/{reply}', 'RepliesController@update')->name('update-reply');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('favorite-reply');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@delete')->name('delete-reply');


Route::get('/profiles/{user}', 'ProfilesController@show')->name('user-profile');
