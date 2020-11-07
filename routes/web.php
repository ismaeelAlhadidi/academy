<?php

use Illuminate\Support\Facades\Route;
use App\Models\Video;
use App\Models\Subscription;
use App\Jobs\SendMailsAndNotificationToUsers;
use App\Streaming\VideoStream;
//use FFMpeg;

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
Route::get('/blob/video/{video}',function($video) {
    if(! auth('admin')->check()) return abort('404');
    $temp = new App\Http\Controllers\BlobController();
    return $temp->getVideo($video);
});
Route::get('/blob/audio/{audio}',function($audio) {
    if(! auth('admin')->check()) return abort('404');
    $temp = new App\Http\Controllers\BlobController();
    return $temp->getAudio($audio);
});
Route::group(['prefix' => 'object', 'middleware' => 'access'], function() {
    Route::get('/video/{video}','BlobController@getWatch');
    Route::get('/audio/{audio}','BlobController@getAudio');
    Route::get('/book/{book}','BlobController@getBook');
});

Route::get('/', 'WelcomeController@index')->name('welcome')->middleware('guest');
Route::get('/getOpinionsOfPlaylist/{id}', 'WelcomeController@getOpinionsOfPlaylist');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/my-list', 'HomeController@myList')->name('my.playlists');
Route::get('/ajax/{playlistId}/blob/check-permision/{id}','BlobController@checkBlobPermision')->middleware('auth');

Route::group(['middleware' => 'auth', 'namespace' => 'Authenticated'],function() {
    Route::group(['middleware' => 'u.notifcation'],function() {
        Route::group(['prefix' => 'playlist'],function() {
            Route::get('/{id?}', 'PlaylistController@index')->name('playlist.show');
        });
        Route::get('/sessions', 'SessionController@index')->name('sessions');
        Route::get('/profile/{id?}', 'UserController@index')->name('user.profile');
        Route::get('/my-sessions', 'UserController@mySessions')->name('my.sessions');
    });

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
        Route::group(['prefix' => 'notification'],function () {
            Route::get('/setReaded/{type}/{id}','NotificationController@setReaded');
            Route::get('/replay/{id}','NotificationController@getReplay');
            Route::get('/session-online/{id}/offer','NotificationController@getOffer');
        });
    });
});

Route::post('/pay-playlist', 'PaymentController@paywithpaypal')->name('paywithpaypal');
Route::get('/pay-status', 'PaymentController@getPaymentStatus')->name('pay.status');

Route::get('/public/form/{key?}', 'WelcomeController@getForm')->name('public.form');
Route::post('/public/form/{key?}', 'WelcomeController@saveForm')->name('save.public.form');

Auth::routes(["verify" => "true"]);

Route::get('/test', 'BlobController@testHls');
Route::get('/test/video', function() {
    $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . 'playlist1\T0ut5i5f5068b8287d75-19494637-1599105208.m3u8';
    //$video = new VideoStream(storage_path('app' . $path), 'local');
    //return response()->stream($video->start());
    return response()->file(storage_path('app' . $path));
});
Route::get('/test/{file}', function($file) {
    $tempArray = explode('_', $file);
    if(! session()->has($tempArray[0])) abort('404');
    $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . session()->get($tempArray[0]) . DIRECTORY_SEPARATOR . $file;
    if (! Storage::disk('local')->exists($path)) return abort('404');
    return response()->file(storage_path('app' . $path));
});

/* 
    Uses HTTPS
    Add `rel="noopener"` or `rel="noreferrer"` to any external links to improve performance and prevent security vulnerabilities 
        var handelResponse = function handelResponse(response){
            var currentFile = response;
            var video = document.createElement('video');
            blobUrl = URL.createObjectURL(currentFile);
            video.src = blobUrl;
            document.body.appendChild(video);
        };
        var get = function get(url, onResponse, TOKEN) {
            var request = new XMLHttpRequest();
            var formData = new FormData();
            formData.append('_token', TOKEN);
            request.open('post', url);
            request.responseType = 'blob';
            request.onload = function () {
                onResponse(request.response);
            };
            request.send(formData);
        }
        get('http://127.0.0.1:8000/blob/video/UE2wz0-1599105084-YW5D1tLDYOp-5f50683c822881-78597172', handelResponse, 'DRPGp89sQBNH1zhyi73yDGBzsitzLJF5w9U8qhSH');
    */