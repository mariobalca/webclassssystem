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

Route::get('websites/', 'WebsitesController@index')->name('websites.index');
Route::get('websites/{website}', 'WebsitesController@show')->name('websites.show');
Route::post('websites', 'WebsitesController@store')->name('websites.store');
