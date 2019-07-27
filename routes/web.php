<?php

use Intervention\Image\ImageManagerStatic as Image;
use EasyWeChat\Factory;
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

Route::get('/wxoauth', function () {
    $openPlatform = Factory::openPlatform(config('wechat.open_platform'));
    return $openPlatform->getPreAuthorizationUrl('https://readfollow.com/oauth/wxopen');
});

Route::post('/oauth/wxopen', function () { // 授权事件接收url  https://readfollow.com/oauth/wxopen
    $openPlatform = Factory::openPlatform(config('wechat.open_platform'));
    return $openPlatform->server->serve();
});


Route::get('/x', function () {
    dd(config('wechat.open_platform'));
    return view('welcome');
});

Route::get('/','HomeController@index');

Route::get('/poster','Api\ArticleController@poster');

Route::get('/qrcode/article/{token}','Api\QrcodeController@article');

Route::get('/poster','Api\ArticleController@poster');

Route::get('/articles','Api\ArticleController@index');