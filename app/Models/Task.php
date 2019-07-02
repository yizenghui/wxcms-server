<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    //
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','did','appid',
    ];


    // 今日签到
    public function todaySign(){
        if( !$this->todayHasSign() ){
            $this->sign_at = Carbon::now();
            $this->total += $this->todaySignAction();
            return true;
        }
        return false;
    }

    // 今日激励(签到激励)
    public function todayReward(){
        if( !$this->todayHasReward() ){
            $this->reward_at = Carbon::now();
            $this->total += $this->todayRewardAction();
            return true;
        }
        return false;
    }
    
    // 今日阅读加分数
    public function todayReadAction(){
        if($this->todayHasTeam()) return config('point.read_action') * 2;
        return config('point.read_action');
    }

    // 今日点赞加分数
    public function todayLikeAction(){
        if($this->todayHasTeam()) return config('point.like_action') * 2;
        return config('point.like_action');
    }

    // 用户今日激励文章加分数
    public function todayRewardArticleAction(){ //激励文章
        return config('point.reward_article_action');
    }

    // 作者文章被激励加分数
    public function todayAuthorArticleRewardAction(){ //激励作者(不设上限,在用户激励行为奖励中并行)
        return config('point.author_article_reward_action');
    }

    // 今日签到积分
    public function todaySignAction(){
        return config('point.sign_action');
    }
    
    // 今日激励积分
    public function todayRewardAction(){ // reward_at
        return config('point.reward_action');
    }
    
    // 今日组队成功?
    public function todayHasTeam(){
        if($this->team_id) return true;
        return false;
    }

    // 今日已签到
    public function todayHasSign(){
        if($this->sign_at) return true;
        return false;
    }

    // 今日签到已激励
    public function todayHasReward(){
        if($this->reward_at) return true;
        return false;
    }

    // 今日阅读加1,未受限时返回true
    public function todayReadAdd(){
        $this->read_num ++;
        if( !$this->todayReadMax() ){
            $this->total += $this->todayReadAction();
            return true;
        }
        return false;
    }

    // 今日阅读加1,未受限时返回true
    public function todayLikeAdd(){
        $this->like_num ++;
        if( !$this->todayLikeMax() ){
            $this->total += $this->todayLikeAction();
            return true;
        }
        return false;
    }

    // 今日文章激励加1,未受限时返回true
    public function todayRewardArticleAdd(){ // 激励数加1
        $this->reward_num ++;
        if( !$this->todayRewardArticleMax() ){
            $this->total += $this->todayRewardArticleAction();
            return true;
        }
        return false;
    }

    // 今日阅读任务数(最大显示每日可完成最大次数)
    public function todayRead(){
        if( $this->todayReadMax() ) return config('point.day_read_num');
        return intval($this->read_num);
    }

    public function todayReadMax(){
        if( $this->read_num>config('point.day_read_num') ) return true;
        return false;
    }

    // 今日点赞任务数(最大显示每日可完成最大次数)
    public function todayLike(){
        if( $this->todayLikeMax() ) return config('point.day_like_num');
        return intval($this->like_num);
    }

    public function todayLikeMax(){
        if( $this->like_num>config('point.day_like_num') ) return true;
        return false;
    }

    
    // 今日文章激励任务数(最大显示每日可完成最大次数)
    public function todayRewardArticle(){
        if( $this->todayRewardArticleMax() ) return config('point.day_reward_article_num');
        return intval($this->reward_num);
    }

    // 今日文章激励任务完成?
    public function todayRewardArticleMax(){
        if( $this->reward_num>config('point.day_reward_article_num') ) return true;
        return false;
    }

    
    
    // 今日邀请加1,未受限时返回true
    public function todayInterviewAdd(){
        $this->join_num ++;
        if( !$this->todayInterviewMax() ){
            $this->total += $this->todayInterviewAction();
            return true;
        }
        return false;
    }

    // 今日邀请新用户加分数(不享受组队加成)
    public function todayInterviewAction(){
        return config('point.interview_action');
    }

    // 今日邀请新用户人数(最大显示每日可完成最大次数)
    public function todayInterview(){
        if( $this->todayInterviewMax() ) return config('point.day_interview_num');
        return intval($this->join_num);
    }

    public function todayInterviewMax(){
        if( $this->join_num>config('point.day_interview_num') ) return true;
        return false;
    }
    
    
    // 今日分享访问加1,未受限时返回true
    public function todayShareAdd(){
        $this->share_num ++;
        if( !$this->todayShareMax() ){
            $this->total += $this->todayShareAction();
            return true;
        }
        return false;
    }

    // 今日分享访问加分数(不享受组队加成)
    public function todayShareAction(){
        return config('point.share_action');
    }

    // 今日分享访问人数(最大显示每日可完成最大次数)
    public function todayShare(){
        if( $this->todayShareMax() ) return config('point.day_share_num');
        return intval($this->share_num);
    }

    public function todayShareMax(){
        if( $this->share_num > config('point.day_share_num') ) return true;
        return false;
    }
    

    // 今日粉丝签到加1,未受限时返回true
    public function todayFansignAdd(){
        $this->sign_num ++;
        if( !$this->todayFansignMax() ){
            $this->total += $this->todayFansignAction();
            return true;
        }
        return false;
    }

    // 今日点赞加分数(不享受组队加成)
    public function todayFansignAction(){
        return config('point.fansign_action');
    }
    // 今日受邀人签到分成
    public function todayFansign(){
        if( $this->todayFansignMax() ) return config('point.day_fansign_num');
        return intval($this->sign_num);
    }

    public function todayFansignMax(){
        if( $this->sign_num>config('point.day_fansign_num') ) return true;
        return false;
    }

    
    /**
     * 有队伍的人了
     */
    public function team()
    {
        return $this->belongsTo(Team::class,'team_id');
    }
}
