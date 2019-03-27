<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','did',
    ];


    public function todaySigned(){
        
    }

    
    // 今日阅读加分数（如果有组队双倍得分）
    public function todayReadAction(){
        if($this->todayHasTeam()) return config('point.read_action') * 2;
        return config('point.read_action');
    }

    // 今日点赞加分数（如果有组队双倍得分）
    public function todayLikeAction(){
        if($this->todayHasTeam()) return config('point.like_action') * 2;
        return config('point.like_action');
    }
    // 今日组队成功?
    public function todayHasTeam(){
        if($this->team_id) return true;
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
}
