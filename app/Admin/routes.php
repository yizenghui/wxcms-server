<?php

use Illuminate\Routing\Router;


Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->resource('auth/users', 'CustomUserController')->names('admin.auth.users');

    $router->get('/', 'HomeController@index');
    $router->get('article/createInitData', 'ArticleController@createInitData');

    $router->get('article/quickcreate', 'ArticleController@quickCreate');
    $router->resource('article', ArticleController::class);
    $router->resource('author', AuthorController::class);
    $router->resource('topic', TopicController::class);
    $router->resource('goods', GoodsController::class);
    $router->resource('order', OrderController::class);
    $router->resource('fund', FundController::class);
    $router->resource('fan', FanController::class);
    $router->resource('boss', BossController::class);
    $router->resource('ad', AdController::class);
    $router->resource('app', AppController::class);
    $router->resource('carousel', CarouselController::class);
    $router->resource('share', ShareController::class);
    $router->resource('visitor', VisitorController::class);
    $router->resource('comment', CommentController::class);

});
