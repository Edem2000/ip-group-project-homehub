<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', 'PagesController@getIndex')->name('/');
Route::get('/about', 'PagesController@getAbout')->name('about');
Route::get('/dashboard', 'PagesController@getDashboard')->name('dashboard');
// Create all routes associated with posts
Route::resource('posts', 'PostsController');

