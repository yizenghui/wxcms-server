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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/poster','Api\ArticleController@poster');

Route::get('/qrcode/article/{token}','Api\QrcodeController@article');

Route::get('/poster','Api\ArticleController@poster');
