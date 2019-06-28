<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Fan;
use App\Models\Task;

class ActionController extends Controller
{
    
    public function task(Request $request){
        $data = (new \App\Repositories\ActionRepository())->GetUserTask($request->user());
        return response()->json($data);
    }

  

    public function view(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        $data = (new \App\Repositories\ActionRepository())->UserView($request->user(),$article);
        return response()->json($data);
    }


    /**
     * 喜欢某个文章
     */
    public function likeArticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        $data = (new \App\Repositories\ActionRepository())->UserLikeArticle($request->user(),$article);
        return response()->json($data);
    }
    
    /**
     * 激励文章
     */
    public function rewardArticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        $data = (new \App\Repositories\ActionRepository())->UserRewardArticle($request->user(),$article);
        return response()->json($data);
    }
    
    /**
     * 签到
     */
    public function sign(Request $request){
        $user = (new \App\Repositories\ActionRepository())->DoSign( $request->user());
        return response()->json($user);
    }
    
    /**
     * 激励
     */
    public function  reward(Request $request){
        $user = (new \App\Repositories\ActionRepository())->DoReward( $request->user());
        return response()->json($user);
    }
    /**
     * 激励签到
     */
    public function  signAndReward(Request $request){
        $user = (new \App\Repositories\ActionRepository())->DoRewardAndSign( $request->user());
        return response()->json($user);
    }
    /**
     * 取消喜欢某个文章
     */
    public function unLikeArticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        $data = (new \App\Repositories\ActionRepository())->UserUnLikeArticle($request->user(),$article);
        return response()->json($data);
    }
}
