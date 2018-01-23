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

Route::get('/','WelcomeController@Index');
Route::post('/search',['as'=>'search','uses'=>'WelcomeController@Search']);

Route::get('/test','WelcomeController@Test');

Route::get('/login','AuthorizationController@Index');
Route::get('/logout',['as'=>'logout','uses'=>'AuthorizationController@logout']);
Route::get('/callback','AuthorizationController@Callback');
Route::get('/refreshtoken','AuthorizationController@refreshToken');//uçur

Route::get('/device','DeviceController@Index');
Route::post('/device',['as'=>'device.connectDevice','uses'=>'DeviceController@connectDevice']);

Route::get('/playlist','PlaylistController@Index');
Route::post('/playlist',['as'=>'playlist.shareList','uses'=>'PlaylistController@shareList']);
Route::get('/playlist/{jukeboxid}','PlaylistController@getPlayList');
Route::get('/playlist/detail/{playlistid}','PlaylistController@getPlayListDetail');
Route::get('/playlist/track/{track}',['as'=>'playlist.playSong','uses'=>'PlaylistController@playSong']);

Route::resource('/jukebox','JukeBoxController');
Route::get('/jukebox/{Jukebox}/CountTrack',['as'=>'jukebox.counttrack','uses'=>'JukeBoxController@countTrack']);