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
        $task = $user->todaytask();
        $items = [];

        $items[] = ['name'=>'签到 +'.config('point.sign_action'), 'intro'=>$task->sign_at?'已完成':'未完成', 'wxto'=>'/pages/user/index', 'icon'=>'squarecheck', 'iconcolor'=>'red'];
        $items[] = ['name'=>'阅读 +'.config('point.read_action') * config('point.day_read_num'), 'intro'=>$task->todayRead().' / '.config('point.day_read_num').' * '.config('point.read_action'), 'wxto'=>'/pages/index/index', 'icon'=>'attention', 'iconcolor'=>'red'];
        $items[] = ['name'=>'点赞 +'.config('point.like_action') * config('point.day_like_num'), 'intro'=>$task->todayLike().' / '.config('point.day_like_num').' * '.config('point.like_action'), 'wxto'=>'/pages/index/index', 'icon'=>'appreciate', 'iconcolor'=>'red'];
        // $items[] = ['name'=>'','intro'=>'','wxto'=>'','icon'=>'','iconcolor'=>''];
        return response()->json($items);
    }

  

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
            $task = $user->todaytask();
            if($task->todayReadAdd()){
                $user->changePoint($task->todayReadAction(),'阅读');
            }
            $task->save();
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
            if( !$user->hasFavorited($article) ){
                $user->favorite($article);
                $task = $user->todaytask();
                if($task->todayLikeAdd()){
                    $user->changePoint($task->todayLikeAction(),'点赞');
                }
                $task->save();
            }
           
        }
        return '';
    }

    
    /**
     * 签到
     */
    public function sign(Request $request){
        $user = $request->user();
        $task = $user->todaytask();
        if($task->todayHasSign()) return response()->json(['message'=>'重复签到']);
        if($task->todaySign()){
            $user->changePoint($task->todaySignAction(),'签到');
            $task->save();
            
            
            $formid = $user->formid?$user->formid:config('point.default_fromid');
            if($formid){
                $fromuser = Fan::find($formid);
                if($fromuser->id && !$fromuser->lock_at ){
                    $_task = $fromuser->todaytask();
                    if($_task->todayFansignAdd()){
                    $fromuser->changePoint($_task->todayFansignAction(),'受邀用户签到');
                    $_task->save();
                    }
                }
            }

        }
        $user->task = $task;
        return response()->json($user);
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
        if($user->hasLiked($article)){
            $article->liked --;
            $article->save();
            $user->unlike($article);
        }
        return '';
    }
}
