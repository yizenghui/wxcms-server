<?php 
namespace App\Repositories;
use App\Models\App;
use Illuminate\Support\Facades\Log;
use Redis;
use Vinkla\Hashids\Facades\Hashids;

class AppRepository {

    private $appid;

    private $wxapp;
    
    public function __construct($appid = 0){
   
        $this->appid =  $appid;
    }

    // 记录调用了一次
    public function log(){
        $wxapp = $this->getminiapp();
        Log::useFiles( storage_path('logs/'.date('Ym').'/'.$wxapp->id.'/'.date('dH').'.log') );
        $openid = '';
        $user = request()->user();
        if($user) $openid = $user ->openid;
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
            'point.day_fansign_action' => $app->point_day_fansign_action,
            'point.fanreward_action' => $app->point_fanreward_action, 
            'point.team_double_enabled' => $app->point_day_team_double_enabled,
            'point.day_sign_num' => $app->point_day_sign_num,
            'point.day_reward_num' => $app->point_day_reward_num,
            'point.day_fansign_num' => $app->point_day_fansign_num,
            'point.day_fanreward_num' => $app->point_day_fanreward_num,
            'point.day_interview_num' => $app->point_day_interview_num,
            'point.interview_action' => $app->point_interview_action,
            'point.like_action' => $app->point_like_action,
            'point.day_like_num' => $app->point_day_like_num,
            'point.day_read_num' => $app->point_day_read_num,
            'point.read_action' => $app->point_read_action,
            'point.day_share_num' => $app->point_day_share_num,
            'point.share_action' => $app->point_share_action,
            'point.channel_status' => $app->point_channel_status,
            'point.show_task' => $app->point_show_task,
            'point.score_type' => $app->point_score_type, // 积分类型标题
            'point.score_ratio' => $app->point_score_ratio, // 积分前后台比值
            'point.reward_article_action' => $app->point_reward_article_action, // 激励文章行为
            'point.day_reward_article_num' => $app->point_day_reward_article_num, // 一天最多可以激励多少次(获得奖励)
            'point.rereading_reward' => $app->point_rereading_reward, // 重复阅读同一篇文章给予奖励
            'point.repeated_incentives' => $app->point_repeated_incentives, //重复激励同一篇文章给予奖励
            'point.author_article_reward_action' => $app->point_author_article_reward_action, //作者文章被激励(加分给作者次数不设上限)
            ]);
    }

    public function getconfig(){
        $app = $this->getminiapp();
        $config = [
        'app_id' => $app->app_id,
        'secret' => $app->app_secret,
        'refresh_token' => $app->refresh_token,
        'follow_status' => $app->follow_status, // 关注组件状态
        'reward_adid' => $app->reward_adid,
        'banner_adid' => $app->banner_adid,
        'screen_adid' => $app->screen_adid,
        'rank_status' => $app->rank_status,
        'shopping_status' => $app->shopping_status,
        'point_logs_status' => $app->point_logs_status,
        'reward_status' => $app->reward_status,
        'point_sign_action' => $app->point_day_sign_num?$app->point_sign_action:0,
        'point_reward_article_action' => $app->point_reward_article_action?$app->point_reward_article_action:0,
        'point_reward_action' => $app->point_day_reward_num?$app->point_reward_action:0,
        'default_fromid' =>$app->point_enabled ? $app->point_default_fromid : 0,
        'default_search' =>$app->default_search ? $app->default_search:'标题搜索',
        'index_share_title'=>$app->indexshare?$app->indexshare->OneTitle:'',
        'index_share_cover'=>$app->indexshare?$app->indexshare->OneCover:'',
        'topic_share_title'=>$app->topicshare?$app->topicshare->OneTitle:'',
        'topic_share_cover'=>$app->topicshare?$app->topicshare->OneCover:'',
        'score_type' => $app->point_score_type, // 积分类型标题
        'score_ratio' => $app->point_score_ratio, // 积分前后台比值
        'template_topic'=>$app->template_topic?$app->template_topic:'green', // 默认绿色主题
        'show_poster_btn'=>$app->show_poster_btn,
        ];
        return $config;
    }

    public function getappid(){
        if($this->appid) return $this->appid;
        $ids = Hashids::decode( request()->server('HTTP_API_TOKEN') );//api_token
        if($ids[1]>20190101){
            $this->appid = intval($ids[0]);
            return $this->appid;
        }
        return 0;
    }
    
    
    public function getwxapp(){
        return  $this->getminiapp();
    }

    public function getminiapp(){
        if($this->wxapp) return $this->wxapp;
        // Redis缓存使用有问题，暂时还是别搞了
        // serialize() unserialize()
        // $app = unserialize( Redis::get("app:".$this->getappid()) );
        // dd($app);
        // if(!$app) 
        $this->wxapp = App::where('appid','=',$this->getappid())->firstOrFail();
        // Redis::set("app:".$this->getappid(), serialize($app));
        return $this->wxapp;
    }
}