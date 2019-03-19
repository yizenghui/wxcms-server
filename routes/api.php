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

Route::get('gettoken','Api\AuthController@token');

Route::group(['middleware' => ['auth:api']], function () {
    

    Route::get('checktoken','Api\AuthController@check');

    Route::get('/action/view','Api\ActionController@view'); // 查看文章行为


    Route::get('/articles','Api\ArticleController@index');
    Route::get('/articles/recommend','Api\ArticleController@recommend');
    Route::get('/articles/{id}','Api\ArticleController@show');
    Route::get('/topics','Api\TopicController@index');
    Route::get('/topics/{id}','Api\TopicController@show');
});


// Route::middleware(['auth:api'])->get('/checktoken', function (Request $request) {
//     return $request->user();
// });
