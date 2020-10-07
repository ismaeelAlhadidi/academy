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

Route::get('/', 'WelcomeController@index')->middleware('guest');
Route::get('/getOpinionsOfPlaylist/{id}', 'WelcomeController@getOpinionsOfPlaylist');

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth', 'namespace' => 'Authenticated'],function() {
    Route::group(['prefix' => 'playlist'],function() {
        Route::get('/{id?}', 'PlaylistController@index')->name('playlist.show');
        Route::get('/playlist/subscription/{id?}', 'PlaylistController@subscription')->name('playlist.subscription');
    });
    Route::get('/sessions', 'SessionController@index')->name('sessions');
    /****************************  AJAX Requests ****************************/
    Route::group(['prefix' => 'ajax'],function() {
        Route::get('/session-offer/{id}', 'SessionController@getSessionOfferData');
        Route::post('/session-offer/request', 'SessionController@requestSession');
    });
});

Route::post('/pay-playlist', 'PaymentController@paywithpaypal')->name('paywithpaypal');
Route::get('/pay-status', 'PaymentController@getPaymentStatus')->name('pay.status');

Auth::routes(["verify" => "true"]);
/* 
    Uses HTTPS
    Add `rel="noopener"` or `rel="noreferrer"` to any external links to improve performance and prevent security vulnerabilities 
*/