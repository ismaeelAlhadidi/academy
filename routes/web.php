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
Route::group(['prefix' => 'blob'], function() { // ,'middleware' => 'access'
    Route::get('/video/{video}','BlobController@getVideo');
    Route::get('/audio/{audio}','BlobController@getAudio');
    Route::get('/book/{book}','BlobController@getBook');
});

Route::get('/', 'WelcomeController@index')->middleware('guest');
Route::get('/getOpinionsOfPlaylist/{id}', 'WelcomeController@getOpinionsOfPlaylist');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/my-list', 'HomeController@myList')->name('my.playlists');
Route::get('/ajax/{playlistId}/blob/check-permision/{id}','BlobController@checkBlobPermision')->middleware('auth');
Route::group(['middleware' => 'auth', 'namespace' => 'Authenticated'],function() {
    Route::group(['prefix' => 'playlist'],function() {
        Route::get('/{id?}', 'PlaylistController@index')->name('playlist.show');
    });
    Route::get('/sessions', 'SessionController@index')->name('sessions');
    Route::get('/profile/{id?}', 'UserController@index')->name('user.profile');
    Route::get('/my-sessions', 'UserController@mySessions')->name('my.sessions');

    /****************************  AJAX Requests ****************************/
    Route::group(['prefix' => 'ajax'],function() {
        Route::get('/session-offer/{id}', 'SessionController@getSessionOfferData');
        Route::post('/session-offer/request', 'SessionController@requestSession');
        Route::post('/profile/save-changes', 'UserController@saveChanges');
        Route::post('/profile/change-image', 'UserController@changeImage');
        Route::post('/post-opinion/playlist/{id}', 'UserController@postOpinionOfPlaylist');
        Route::post('/post-opinion/coach', 'UserController@postOpinionOfCoach');
        Route::post('/post-opinion/coach', 'UserController@postOpinionOfCoach');
        Route::post('/playlist/{id}/post-comment/', 'UserController@postComment' );
        Route::get('/playlist/{id}/more-comment/', 'PlaylistController@getMoreComments');
        Route::post('/playlist/{id}/post-replay/', 'UserController@postReplay' );
    });
});

Route::post('/pay-playlist', 'PaymentController@paywithpaypal')->name('paywithpaypal');
Route::get('/pay-status', 'PaymentController@getPaymentStatus')->name('pay.status');

Auth::routes(["verify" => "true"]);
/* 
    Uses HTTPS
    Add `rel="noopener"` or `rel="noreferrer"` to any external links to improve performance and prevent security vulnerabilities 
*/