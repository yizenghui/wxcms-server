<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['force-json', 'auth:api']], function () {
//     // put your router
// });



Route::get('/getswipers', function (Request $request) {
    return [
        ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
        ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
        ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
    ];
});

Route::get('gettoken','API\AuthController@token');

Route::group(['middleware' => ['auth:api']], function () {
    

    // Route::get('checktoken','API\AuthController@check');

    Route::get('/action/view','API\ActionController@view'); // 查看文章行为
    Route::get('/action/likearticle','API\ActionController@likearticle'); // 喜欢某个文章
    Route::get('/action/unlikearticle','API\ActionController@unlikearticle'); // 取消喜欢某个文章


    Route::get('/articles','API\ArticleController@index');
    Route::get('/articles/recommend','API\ArticleController@recommend');
    Route::get('/articles/{id}','API\ArticleController@show');
    Route::get('/articles/{id}/likeusers','API\ArticleController@likeusers');
    Route::get('/topics','API\TopicController@index');
    Route::get('/topics/{id}','API\TopicController@show');
});


Route::middleware(['auth:api'])->get('/checktoken', function (Request $request) {
    return $request->user();
});
