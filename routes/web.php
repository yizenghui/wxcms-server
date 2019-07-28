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

Route::get('wxoauth','WxOauthController@authorization'); //前往获取授权
Route::get('wxoauth/callback', 'WxOauthController@callback'); //授权回调接口
Route::get('wxoauth/commitCode', 'WxOauthController@commitCode'); //提交代码
Route::get('wxoauth/submitAudit', 'WxOauthController@submitAudit'); //提交审核
Route::get('wxoauth/getQrCode', 'WxOauthController@getQrCode'); //获取体验码
Route::get('wxoauth/code', 'WxOauthController@code'); // 代码管理


Route::get('/x', function () {
    return view('welcome');
});

Route::get('/','HomeController@index');

Route::get('/poster','Api\ArticleController@poster');

Route::get('/qrcode/article/{token}','Api\QrcodeController@article');

Route::get('/poster','Api\ArticleController@poster');

Route::get('/articles','Api\ArticleController@index');