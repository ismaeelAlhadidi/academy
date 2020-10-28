<?php

use Illuminate\Support\Facades\Route;
Route::group(['namespace' => 'Admin'],function() {
    Route::group(['middleware' => ['auth:admin','a.notifcation']],function() {
        Route::group(['prefix' => 'home'],function() {
            Route::get('/','HomeController@index')->name('admin.home');
        });
        Route::group(['prefix' => 'playlist'],function () {
            Route::get('/','PlaylistController@index')->name('admin.playlist');
            Route::get('/add','PlaylistController@add')->name('admin.playlist.add');
            Route::get('/update/{playlist_id}','PlaylistController@update')->name('admin.playlist.update');
        });
        Route::group(['prefix' => 'session-offer'],function () {
            Route::get('/','OfferController@index')->name('admin.session-offer');
        });
        Route::group(['prefix' => 'sessions'],function() {
            Route::get('/','SessionController@index')->name('admin.sessions');
        });
        Route::group(['prefix' => 'single-videos'],function() {
            Route::get('/','SingleVideoController@index')->name('admin.single-videos');
        });
        Route::get('/app-setting','AppController@index')->name('admin.app.settings');
        Route::post('/app-setting','AppController@store')->name('admin.app.settings');
    });

    /****************************  AJAX Requests ****************************/
    Route::group(['middleware' => 'auth:admin'] ,function() {
        Route::group(['prefix' => 'home'],function() {
            Route::get('/users','HomeController@getUsers')->name('admin.home.show.users');
            Route::get('/subscriptions','HomeController@getSubscription')->name('admin.home.show.subscriptions');
            Route::get('/{user_id}/getSubscriptionsOfUser','HomeController@getSubscriptionsOfUser');
            Route::get('/{subscription_id}/toggleUserPlaylistAccess','HomeController@toggleUserPlaylistAccess');
            Route::get('/{user_id}/getOpinionsOfUser','HomeController@getOpinionsOfUser');
            Route::get('/{coach_opinion_id}/toggleAllowCoachOpinion','HomeController@toggleAllowCoachOpinion');
            Route::get('/{playlist_opinion_id}/toggleAllowPlaylistOpinion','HomeController@toggleAllowPlaylistOpinion');
            Route::get('/{user_id}/getCommentsAndReplaysOfThisUser','HomeController@getCommentsAndReplaysOfThisUser');
            Route::get('/{comment_id}/toggleAllowComment','HomeController@toggleAllowComment');
            Route::get('/{replay_id}/toggleAllowReplay','HomeController@toggleAllowReplay');
            Route::get('/{user_id}/getSessionsOnlineOfUser','HomeController@getSessionsOnlineOfUser');
            Route::get('/{session_online_id}/setAdmission','HomeController@setAdmission');
            Route::get('/{user_id}/getViewsOfUser','HomeController@getViewsOfUser');
            Route::get('/{user_id}/getDivicesOfUser','HomeController@getDivicesOfUser');
            Route::get('/{playlist_id}/getUsersOfThisPlaylist','HomeController@getUsersOfThisPlaylist');
            Route::get('/getVisiters','HomeController@getVisiters');
            Route::post('/mail/playlist-users','HomeController@mailToAllPlaylistUsers')->name('mail.all.playlist.users');
            Route::get('/profits','HomeController@getProfits')->name('profits');
        });
        Route::group(['prefix' => 'playlist'],function () {
            Route::get('/{playlist_id}/getOpinionOfThisPlaylist','PlaylistController@getOpinionOfThisPlaylist');
            Route::get('/{playlist_id}/getCommentsWithReplaysOfThisPlaylist','PlaylistController@getCommentsWithReplaysOfThisPlaylist');
            Route::get('/{playlist_id}/ToggleAvailablePlaylist','PlaylistController@ToggleAvailablePlaylist');
            Route::get('/{playlist_id}/deletePlaylist','PlaylistController@deletePlaylist');
            Route::post('/type/store','PlaylistController@newTypeStore')->name('admin.type.store');
            Route::post('/store','PlaylistController@store')->name('admin.playlist.store');
            Route::post('/store/video','PlaylistController@storeVideoInLocal')->name('admin.video.store');
            Route::post('/store/blob/{type?}','PlaylistController@storeBlob')->name('admin.blob.store');
            Route::post('/update','PlaylistController@saveEdit')->name('admin.playlist.save');
            Route::post('/update/video','PlaylistController@updateVideoData');
            Route::post('/update/blob/{type?}','PlaylistController@updateBlobData');
            Route::get('/delete/{type}/{id}','PlaylistController@deleteBlob');
            Route::get('/getTypeNameFromId/{id}','PlaylistController@getTypeNameFromId');
            Route::get('/toggle-special/{playlist_id?}','PlaylistController@toggleSpecialList')->name('playlist.toggle.special');
            Route::post('/add-new-subscription','PlaylistController@addSubscription')->name('playlist.add.new.subscription');
        });
        Route::group(['prefix' => 'session-offer'],function () {
            Route::post('/store','OfferController@store')->name('admin.store-session-offer');
            Route::get('/delete/{id?}','OfferController@delete')->name('admin.delete-session-offer');
            Route::post('/update','OfferController@update')->name('admin.update-session-offer');
            Route::get('/sessions/{id?}','OfferController@getSessions')->name('admin.get-sessions-of-session-offer');
        });
        Route::group(['prefix' => 'notification'],function () {
            Route::get('/setReaded/{type}/{id}','NotificationController@setReaded');
            Route::get('/CoachOpinion/{id}','NotificationController@getCoachOpinion');
            Route::get('/PlaylistOpinion/{id}','NotificationController@getPlaylistOpinion');
            Route::get('/Comment/{id}','NotificationController@getComment');
            Route::get('/Replay/{id}','NotificationController@getReplay');
            Route::get('/SessionsOnline/{id}','NotificationController@getSessionsOnline');
        });
        Route::group(['prefix' => 'single-videos'],function() {
            Route::post('/add','PlaylistController@storeVideoInLocal')->name('admin.single-videos.add');
            Route::get('/get-users/{id}','SingleVideoController@getUsersData');
        });
    });
    /****************************  ######### ****************************/

    Route::get('/','LoginController@index')->middleware('guest:admin')->name('admin.login');
    Route::post('/','LoginController@login')->middleware('guest:admin')->name('admin.login');

    Route::get('/logout','LoginController@logout')->middleware('auth:admin')->name('admin.logout');
});
