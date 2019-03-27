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
        $user = $request->user();
        // 每日组队、签到、阅读、点赞、评论、吸新、粉丝签到
        $task = Task::firstOrNew(['user_id' => $user->id,'did'=>date('Ymd')]);
        $items = [];
        $items[] = ['name'=>'签到 +30', 'intro'=>$task->sign_at?'已完成':'未完成', 'wxto'=>'', 'icon'=>'squarecheck', 'iconcolor'=>'red'];
        $items[] = ['name'=>'阅读 +2', 'intro'=>$task->read_num>config('point.day_read_num')?config('point.day_read_num'):$task->read_num.' / 10 * 2', 'wxto'=>'', 'icon'=>'attention', 'iconcolor'=>'red'];
        $items[] = ['name'=>'点赞 +5', 'intro'=>$task->like_num>config('point.day_like_num')?config('point.day_like_num'):$task->like_num.' / 6 * 5', 'wxto'=>'', 'icon'=>'appreciate', 'iconcolor'=>'red'];
        // $items[] = ['name'=>'','intro'=>'','wxto'=>'','icon'=>'','iconcolor'=>''];
        return response()->json($items);
    }

  
    // /*
    //  * 积分系统是否启动?
    //  */
    // 'enabled' => env('POINT_ENABLED', true),

    // /*
    //  * 签到可以获利积分
    //  */
    // 'sign_action' => env('POINT_SIGN_ACTION', 30),

    // /**
    //  * 一天可以获得1次签到签到积分
    //  */
    // 'day_sign_num' => env('POINT_DAY_SIGN_NUM', 1),
    
    // /*
    //  * 阅读可以获得积分
    //  */
    // 'read_action' => env('POINT_READ_ACTION', 2),

    // /*
    //  * 一天可以获得10次阅读积分
    //  */
    // 'day_read_num' => env('POINT_DAY_READ_NUM', 10),

    
    // /*
    //  * 点赞可以获得积分
    //  */
    // 'like_action' => env('POINT_LiKE_ACTION', 2),

    // /*
    //  * 一天可以获得6次点赞积分
    //  */
    // 'day_like_num' => env('POINT_DAY_LiKE_NUM', 6),

    
    // /*
    //  * 组队双倍积分功能是否开启
    //  */
    // 'team_double_enabled' => env('POINT_TEAM_DOUBLE_ENABLED', false),

    public function view(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        // $this->userView($request->user(),$article);
        return response()->json($this->userView($request->user(),$article));
    }

    public function userView(Fan $user,Article $article){
        // changePoint
        $article->view ++;
        $article->save();
        if( !$user->hasBookmarked($article) ){
            $user->bookmark($article);
        }
        return '';
    }

    /**
     * 喜欢某个文章
     */
    public function likearticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        // $this->userView($request->user(),$article);
        return response()->json($this->userLikeArticle($request->user(),$article));
    }
    
    public function userLikeArticle(Fan $user,Article $article){
        $article->liked ++;
        $article->save();
        if( !$user->hasLiked($article) ){
            $user->like($article);
        }
        return '';
    }

    
    /**
     * 喜欢取消某个文章
     */
    public function unlikearticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        // $this->userView($request->user(),$article);
        return response()->json($this->userUnLikeArticle($request->user(),$article));
    }
    
    public function userUnLikeArticle(Fan $user,Article $article){
        $article->liked --;
        $article->save();
        if($user->hasLiked($article)){
            $user->unlike($article);
        }
        return '';
    }
}
