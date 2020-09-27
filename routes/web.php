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
Route::group(['prefix' => 'blob' ,'middleware' => 'access'],function() { 
    Route::get('/video/{video}','BlobController@getVideo');
    Route::get('/audio/{audio}','BlobController@getAudio');
    Route::get('/book/{book}','BlobController@getBook');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
/* 
    Uses HTTPS
    Add `rel="noopener"` or `rel="noreferrer"` to any external links to improve performance and prevent security vulnerabilities 
*/
Route::get('/home', 'HomeController@index')->name('home');
