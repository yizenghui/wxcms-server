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


Route::group(['middleware' => ['auth:api']], function () {
    

    Route::get('gettoken','API\AuthController@token');
    // Route::get('checktoken','API\AuthController@check');

    Route::get('/action/view','API\ActionController@view'); // 查看文章行为


    Route::get('/articles','API\ArticleController@index');
    Route::get('/articles/recommend','API\ArticleController@recommend');
    Route::get('/articles/{id}','API\ArticleController@show');
    Route::get('/topics','API\TopicController@index');
    Route::get('/topics/{id}','API\TopicController@show');
});


Route::middleware(['auth:api'])->get('/checktoken', function (Request $request) {
    return $request->user();
});
