<?php

use Illuminate\Http\Request;
use EasyWeChat\Factory;


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// 消息与事件接收URLhttps://readfollow.com/api/wxcallback/$APPID$
Route::post('/wxcallback/{appid}', function (Request $request) {
    $openPlatform = Factory::openPlatform(config('wechat.open_platform'));
    return $openPlatform->server->serve();
});

// Route::group(['middleware' => ['force-json', 'auth:api']], function () {
//     // put your router
// });

Route::get('/v1/qrcode/jump/{token}','Api\QrcodeController@jump');

Route::group(['prefix' => 'v1', 'namespace' => 'Api', 'middleware' => ['checkapp','writeapplog']], function () {

    Route::get('carousels','CarouselController@index');

    Route::get('ads','AdController@index');

    Route::get('config/userhome','AuthController@userhome');
    Route::get('config/teamhome','AuthController@teamhome');
    Route::get('config/home','AuthController@home');
    Route::get('config/topichome','AuthController@topichome');
    Route::get('config/topiclist','AuthController@topiclist');
    Route::get('config/articleinfo','AuthController@articleinfo');

    Route::group(['middleware' => ['initappconfig']], function () {
        Route::get('gettoken','AuthController@token');
    });
     
    Route::get('checktoken','AuthController@checkToken');
    
    Route::group(['middleware' => ['auth:api', 'initappconfig']], function () {
        Route::get('/action/task','ActionController@task'); // 用户每日任务
        Route::get('/action/view','ActionController@view'); // 查看文章行为
        Route::get('/action/rewardarticle','ActionController@rewardArticle'); // 激励文章行为
        Route::get('/action/sign','ActionController@sign'); // 用户签到
        Route::get('/action/reward','ActionController@reward'); // 用户激励
        Route::get('/action/signandreward','ActionController@signAndReward'); // 用户签到激励
        Route::get('/action/likearticle','ActionController@likeArticle'); // 喜欢某个文章
        Route::get('/action/unlikearticle','ActionController@unLikeArticle'); // 取消喜欢某个文章
    });

    Route::group(['middleware' => ['auth:api']], function () {
        

        // Route::get('checktoken','Api\AuthController@check');


        Route::get('/articles','ArticleController@index');
        Route::get('/search','ArticleController@search');
        Route::get('/goodses','GoodsController@index');
        Route::get('/buygoods','GoodsController@buy');
        Route::get('/articles/recommend','ArticleController@recommend');
        Route::get('/articles/{id}','ArticleController@show');
        Route::get('/articles/{id}/likeusers','ArticleController@likeusers');
        Route::get('/articles/{id}/rewardusers','ArticleController@rewardusers');
        Route::get('/topics','TopicController@index');
        Route::get('/topics/{id}','TopicController@show');
        Route::get('/authors','AuthorController@index');
        Route::get('/authors/{id}','AuthorController@show');
        Route::get('/orders/{id}','OrderController@show');
        
        Route::get('/poster/article/{id}','PosterController@article');
        
        //评论
        Route::get('/article/{commentable_id}/comments','CommentController@article');
        Route::post('/article/{commentable_id}/comments','CommentController@postArticle');
        
        Route::get('/comments/{id}/like','CommentController@like');
        Route::get('/comments/{id}/unlike','CommentController@unlike');
        Route::get('/comments/{id}/cancelVote','CommentController@cancelVote'); //取消投票
        Route::get('/comments/{id}/upvote','CommentController@upvote'); //投票赞同
        Route::get('/comments/{id}/downvote','CommentController@downvote'); //投票否定
        
        Route::get('/team/create','TeamController@create');
        Route::get('/team/show','TeamController@show');
        Route::get('/team/search','TeamController@search');
        Route::get('/team/join','TeamController@join');
        Route::get('/team/getme','TeamController@getme');

        Route::post('/asyncuserdata','AuthController@asyncuserdata');
        Route::get('/user/footprint','FanController@footprint');
        Route::get('/user/rank','FanController@rank');
        Route::get('/user/like','FanController@like');
        Route::get('/user/reward','FanController@reward');
        Route::get('/user/pointlog','FanController@pointlog');
        Route::get('/user/tasklogs','FanController@tasklog');
        Route::get('/getme','FanController@getme');
        Route::get('/getuserinfo','FanController@getuserinfo');
        Route::get('/orders','FanController@order');
        Route::get('/more/recommend','MoreController@recommend'); // 小程序推荐
        Route::get('/more/effect','MoreController@effect'); // 影响力数据
        Route::get('/more/operational','MoreController@operational'); // 运营数据
        Route::get('/more/partner','MoreController@partner'); // 合作伙伴
    });



});
