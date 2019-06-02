<?php 
namespace App\Repositories;
use App\Models\Article;
use App\Models\Fan;

class ActionRepository{

    
    public function GetUserTask($user){
        $task = $user->todaytask();
        $items = [];
        if(!config('point.enabled')) 
            return [ ['name'=>'暂无活动任务', 'intro'=>'敬请期待', 'wxto'=>'', 'icon'=>'flag', 'iconcolor'=>'red'] ];
        if(config('point.sign_action'))
            $items[] = ['name'=>'签到 +'.config('point.sign_action'), 'intro'=>$task->sign_at?'已完成':'未完成', 'wxto'=>'/pages/user/index', 'icon'=>'squarecheck', 'iconcolor'=>'green'];
        if(config('point.read_action'))
            $items[] = ['name'=>'阅读 +'.config('point.read_action') * config('point.day_read_num'), 'intro'=>$task->todayRead().' / '.config('point.day_read_num').' * '.config('point.read_action'), 'wxto'=>'/pages/index/index', 'icon'=>'attention', 'iconcolor'=>'red'];
        if(config('point.like_action'))
            $items[] = ['name'=>'点赞 +'.config('point.like_action') * config('point.day_like_num'), 'intro'=>$task->todayLike().' / '.config('point.day_like_num').' * '.config('point.like_action'), 'wxto'=>'/pages/index/index', 'icon'=>'appreciate', 'iconcolor'=>'red'];
        
        if(config('point.interview_action'))
            $items[] = ['name'=>'邀请新用户 +'.config('point.interview_action') * config('point.day_interview_num'), 'intro'=>$task->todayInterview().' / '.config('point.day_interview_num').' * '.config('point.interview_action'), 'wxto'=>'', 'icon'=>'friendadd', 'iconcolor'=>'green'];
        if(config('point.fansign_action'))
            $items[] = ['name'=>'受邀用户签到 +'.config('point.fansign_action') * config('point.day_fansign_num'), 'intro'=>$task->todayFansign().' / '.config('point.day_fansign_num').' * '.config('point.fansign_action'), 'wxto'=>'', 'icon'=>'squarecheck', 'iconcolor'=>'green'];
        // $items[] = ['name'=>'组队','intro'=>'组队成功后阅读、点赞得双倍积分','wxto'=>'/pages/user/team','icon'=>'group','iconcolor'=>'green'];
        return $items;
    }
  
    
    public function UserView(Fan $user,Article $article){
        // changePoint
        $article->view ++;
        $article->save();
        if( !$user->hasBookmarked($article) ){
            $user->bookmark($article);
            $task = $user->todaytask();
            if($task->todayReadAdd()){
                $user->changePoint($task->todayReadAction(),'阅读');
                if($task->team_id){
                    $team = $task->team;
                    $team->total += $task->todayReadAction();
                    $team->save();
                }
                if( $article->author_id){
                    $author = $article->author;
                    $author->point ++;
                    $author->total_point ++;
                    $author->save();
                }
                $task->save();
                return ['message'=>'积分+'.$task->todayReadAction()];
            }
            $task->save();
        }
        return ['message'=>'阅读量+1'];
    }

    
    public function UserLikeArticle(Fan $user,Article $article){
        $article->liked ++;
        $article->save();
        if( !$user->hasLiked($article) ){
            $user->like($article);
            if( !$user->hasFavorited($article) ){
                $user->favorite($article);
                $task = $user->todaytask();
                if($task->todayLikeAdd()){
                    $user->changePoint($task->todayLikeAction(),'点赞');
                    if($task->team_id){
                        $team = $task->team;
                        $team->total += $task->todayLikeAction();
                        $team->save();
                    }
                    $task->save();
                    return ['message'=>'积分+'.$task->todayLikeAction()];
                }
                $task->save();
            }
            return ['message'=>'点赞+1'];
        }
        return ['message'=>'点赞成功'];
    }

    public function UserUnLikeArticle(Fan $user,Article $article){
        if($user->hasLiked($article)){
            $article->liked --;
            $article->save();
            $user->unlike($article);
            return ['message'=>'点赞-1'];
        }
        return ['message'=>'失败'];
    }
    
    public function DoSign($user){
        $task = $user->todaytask();
        if($task->todayHasSign()) return ['message'=>'重复签到'];
        if($task->todaySign()){
            $user->sign_at = date('Ymd');
            $user->changePoint($task->todaySignAction(),'签到');
            $task->save();
            $formid = $user->formid?$user->formid:config('point.default_fromid');
            if( $formid ){
                $fromuser = Fan::find($formid);
                if( $fromuser->id && !$fromuser->lock_at ){
                    $_task = $fromuser->todaytask();
                    if($_task->todayFansignAdd()){
                        $fromuser->changePoint($_task->todayFansignAction(),'受邀用户签到');
                        $_task->save();
                    }
                }
            }
        }
        $user->task = $task;
        return $user;
    }

    
    public function DoReward($user){
        $task = $user->todaytask();
        if($task->todayHasReward()) return ['message'=>'重复激励'];
        if($task->todayReward()){
            $user->reward_at = date('Ymd');
            $user->changePoint($task->todayRewardAction(),'激励');
            $task->save();
        }
        $user->task = $task;
        return $user;
    }

    // 观看激励视频并签到
    public function DoRewardAndSign($user){
        $task = $user->todaytask();
        if($task->todayHasSign()) return ['message'=>'重复签到'];
        if($task->todayHasReward()) return ['message'=>'重复激励'];
        if($task->todaySign() && $task->todayReward()){
            $user->sign_at = date('Ymd');
            $user->reward_at = date('Ymd');
            $user->changePoint(intval($task->todayRewardAction()) + intval($task->todaySignAction()),'激励签到');
            $task->save();
            $formid = $user->formid?$user->formid:config('point.default_fromid');
            if( $formid ){
                $fromuser = Fan::find($formid);
                if( $fromuser->id && !$fromuser->lock_at ){
                    $_task = $fromuser->todaytask();
                    if($_task->todayFansignAdd()){
                        $fromuser->changePoint($_task->todayFansignAction(),'受邀用户签到');
                        $_task->save();
                    }
                }
            }
        }
        $user->task = $task;
        return $user;
    }
}