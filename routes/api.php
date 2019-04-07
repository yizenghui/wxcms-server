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


Route::get('/getswipers', function (Request $request) {
    return [
        ['name'=>'','cover'=>'https://wx1.wechatrank.com/base64img/20190407105115.jpeg','wxto'=>''],
    ];
});


Route::get('/getuserswipers', function (Request $request) {
    return [
        ['name'=>'','cover'=>'https://wx1.wechatrank.com/base64img/20190407105115.jpeg','wxto'=>''],
    ];
});

Route::get('config/userhome','Api\AuthController@userhome');
Route::get('config/teamhome','Api\AuthController@teamhome');
Route::get('config/home','Api\AuthController@home');
Route::get('config/topichome','Api\AuthController@topichome');
Route::get('config/topiclist','Api\AuthController@topiclist');
Route::get('config/articleinfo','Api\AuthController@articleinfo');

Route::get('gettoken','Api\AuthController@token');

Route::group(['middleware' => ['auth:api']], function () {
    

    // Route::get('checktoken','Api\AuthController@check');

    Route::get('/action/task','Api\ActionController@task'); // 用户每日任务
    Route::get('/action/view','Api\ActionController@view'); // 查看文章行为
    Route::get('/action/sign','Api\ActionController@sign'); // 查看文章行为(用户签到)
    Route::get('/action/likearticle','Api\ActionController@likearticle'); // 喜欢某个文章
    Route::get('/action/unlikearticle','Api\ActionController@unlikearticle'); // 取消喜欢某个文章


    Route::get('/articles','Api\ArticleController@index');
    Route::get('/goodses','Api\GoodsController@index');
    Route::get('/buygoods','Api\GoodsController@buy');
    Route::get('/articles/recommend','Api\ArticleController@recommend');
    Route::get('/articles/{id}','Api\ArticleController@show');
    Route::get('/articles/{id}/likeusers','Api\ArticleController@likeusers');
    Route::get('/topics','Api\TopicController@index');
    Route::get('/topics/{id}','Api\TopicController@show');
    Route::get('/orders/{id}','Api\OrderController@show');
    
    
    Route::get('/team/create','Api\TeamController@create');
    Route::get('/team/show','Api\TeamController@show');
    Route::get('/team/search','Api\TeamController@search');
    Route::get('/team/join','Api\TeamController@join');
    Route::get('/team/getme','Api\TeamController@getme');

    Route::post('/asyncuserdata','Api\AuthController@asyncuserdata');
    Route::get('/user/footprint','Api\FanController@footprint');
    Route::get('/user/like','Api\FanController@like');
    Route::get('/user/pointlog','Api\FanController@pointlog');
    Route::get('/user/tasklogs','Api\FanController@tasklog');
    Route::get('/getme','Api\FanController@getme');
    Route::get('/getuserinfo','Api\FanController@getuserinfo');
    Route::get('/orders','Api\FanController@order');
    Route::get('/more/recommend','Api\MoreController@recommend'); // 小程序推荐
    Route::get('/more/effect','Api\MoreController@effect'); // 影响力数据
    Route::get('/more/operational','Api\MoreController@operational'); // 运营数据
    Route::get('/more/partner','Api\MoreController@partner'); // 合作伙伴
});


Route::middleware(['auth:api'])->get('/checktoken', function (Request $request) {
    return $request->user();
});
