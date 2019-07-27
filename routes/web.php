<?php

use Intervention\Image\ImageManagerStatic as Image;
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

Route::get('wxoauth','WxOauthController@authorization');

Route::get('wxoauth/callback', 'WxOauthController@callback');
Route::get('wxoauth/commitCode', 'WxOauthController@commitCode');
Route::get('wxoauth/getQrCode', 'WxOauthController@getQrCode');
Route::get('wxoauth/code', 'WxOauthController@code');



Route::get('/x', function () {
    dd(config('wechat.open_platform'));
    return view('welcome');
});

Route::get('/','HomeController@index');

Route::get('/poster','Api\ArticleController@poster');

Route::get('/qrcode/article/{token}','Api\QrcodeController@article');

Route::get('/poster','Api\ArticleController@poster');

Route::get('/articles','Api\ArticleController@index');