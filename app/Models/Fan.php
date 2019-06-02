<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanLike;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanSubscribe;
use Overtrue\LaravelFollow\Traits\CanVote;
use Overtrue\LaravelFollow\Traits\CanBookmark;
use Spatie\Activitylog\Traits\LogsActivity;

class Fan extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes, LogsActivity, CanFollow, CanBookmark, CanLike, CanFavorite, CanSubscribe, CanVote;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'openid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'openid',
    ];
    
    protected static $logUnguarded = true;
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 获取当前积分(支持两种策略)
     */
    public function getCurrentPointAttribute($value)
    {
        if(config('point.cycle_clearing')) return $value;
        return $this->point;
    }

    /**
     * 获取用户头像
     */
    public function getAvatarAttribute($value)
    {
        return  $value?$value:'https://wx1.wechatrank.com/base64img/20190408175917.png';
    }
    /**
     * 更改积分记录
     * 变化值,描述,如果有id自动保存
     */
    public function changePoint($change, $intro='', $autosave = true){
        $this->point += $change;
        $this->total_point += $change;
        if($this->id && $autosave){
            if( $this->save() ){
                $log = new PointLog;
                $log->user_id = $this->id;
                $log->tenancy_id = $this->tenancy_id;
                $log->change = $change;
                $log->intro = $intro;
                $log->save();
            }
        }
    }
    
    /**
     * 获取用户的订单
     */
    public function orders()
    {
         return $this->hasMany(Order::class,'user_id');
    }

    /**
     * 用户积分记录
     */
    public function pointlogs()
    {
         return $this->hasMany(PointLog::class,'user_id');
    }


    public function readlogs()
    {
         return $this->bookmarks(\App\Models\Article::class);
    }
    
    public function likelogs()
    {
         return $this->likes(\App\Models\Article::class);
    }
    
    /**
     * 用户所有任务
     */
    public function tasks()
    {
         return $this->hasMany(Task::class,'user_id');
    }
    
    /**
     * 用户今日任务
     */
    public function todaytask()
    {
        return Task::firstOrCreate(['user_id' =>$this->id,'tenancy_id' =>$this->tenancy_id,'did'=>date('Ymd')]);
    }
    /**
     * 用户所创建的队伍
     */
    public function teams()
    {
         return $this->hasMany(Team::class,'user_id');
    }

    
    /**
     * 用户所加入的队伍
     */
    public function joinTeams()
    {
        return $this->belongsToMany(Team::class,'teamables','user_id');
    }


     
    /**
     * 推荐人
     */
    public function fromuser()
    {
        return $this->belongsTo(Fan::class,'fromid');
    }
}
