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

Route::get('/{year?}/{month?}', 'HomeController@index')->name('home');

Route::get('/notes/{year}/{month}/{day}', 'NoteController@index')->name('notes');

Route::post('/notes/{year}/{month}/{day}', 'NoteController@store')->name('notes.store');
Route::put('/notes/{year}/{month}/{day}/{note}', 'NoteController@update')->name('notes.update');
Route::delete('/notes/{year}/{month}/{day}/{note}', 'NoteController@destroy')->name('notes.destroy');
