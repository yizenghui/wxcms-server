<?php 
namespace App\Repositories;
use App\Models\App;
use Illuminate\Support\Facades\Log;
use Redis;

class AppRepository {

    private $appid;

    private $wxapp;
    
    public function __construct($appid){
   
        $this->appid =  $appid;

    }

    // 记录调用了一次
    public function log($wxapp){
        $wxapp->current_quota ++;
        $wxapp->total_quota ++;
        Redis::set("app:".$wxapp->id, $wxapp);
        Log::useFiles( storage_path('logs/'.$wxapp->app_id.'.log') );
        $openid = request()->user()->openid;
        $method = request()->method();
        $path = request()->path();
        Log::info($method.' openid:'.$openid.' path:'.$path);
    }
    
    
/**
 * 
 */
public function initConfig(){
    // InitAppConfig
    $app = $this->getwxapp($this->getappid());
    config([
        'point.enabled' => $app->point_enabled,
        'point.default_fromid' => $app->point_default_fromid,
        'point.sign_action' => $app->point_sign_action,
        'point.reward_action' => $app->point_reward_action,
        'point.fansign_action' => $app->point_day_fansign_action,
        'point.team_double_enabled' => $app->point_day_team_double_enabled,
        'point.day_sign_num' => $app->point_day_sign_num,
        'point.day_reward_num' => $app->point_day_reward_num,
        'point.day_fansign_num' => $app->point_day_fansign_num,
        'point.day_interview_num' => $app->point_day_interview_num,
        'point.interview_action' => $app->point_interview_action,
        'point.like_action' => $app->point_like_action,
        'point.day_like_num' => $app->point_day_like_num,
        'point.day_read_num' => $app->point_day_read_num,
        'point.read_action' => $app->point_read_action,
        ]);
}

  public function getconfig(){
    $app = $this->getminiapp();
    $config = [
      'app_id' => $app->app_id,
      'secret' => $app->app_secret,
      'reward_adid' => $app->reward_adid,
      'banner_adid' => $app->banner_adid,
      'point_sign_action' => $app->point_day_sign_num?$app->point_sign_action:0,
      'point_reward_action' => $app->point_day_reward_num?$app->point_reward_action:0,
      'default_fromid' =>$app->point_enabled ? $app->point_default_fromid : 0,
      'default_search' =>$app->default_search ? $app->default_search:'标题搜索',
      'index_share_title'=>$app->indexshare?$app->indexshare->OneTitle:'',
      'index_share_cover'=>$app->indexshare?$app->indexshare->OneCover:'',
      'topic_share_title'=>$app->topicshare?$app->topicshare->OneTitle:'',
      'topic_share_cover'=>$app->topicshare?$app->topicshare->OneCover:'',
    ];
    return $config;
  }

    public function getappid(){
        return $this->appid;
    }
    
    public function getwxapp(){
        return  $this->getminiapp();
    }

    public function getminiapp(){
        // serialize() unserialize()
        $app = unserialize( Redis::get("app:".$this->getappid()) );
        // dd($app);
        if(!$app) $app = App::where('appid','=',$this->getappid())->firstOrFail();
        Redis::set("app:".$this->getappid(), serialize($app));
        return $app;
    }
}