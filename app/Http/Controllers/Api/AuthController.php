<?php

namespace App\Http\Controllers\Api;

use App\Models\Fan;
use App\Models\Visitor;
use App\Models\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat;
use Auth;
use JWTAuth;
use EasyWeChat\Factory;

class AuthController extends Controller
{
  
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['token']]);
  }

  public function getconfig($appid){
    
    $config = ( new \App\Repositories\AppRepository($appid) )->getconfig();

    return $config;
  }

  // 检查token
  public function checkToken(Request $request){
    return $request->user();
  }
  public function gettoken($js_code,$app_id,$secret){
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$app_id}&secret={$secret}&js_code={$js_code}";
    return json_decode( file_get_contents($url), true );
  }
  //
  public function token(Request $request){
    
    $appid = intval($request->get('appid'));
    $scene = intval($request->get('scene'));
    $config = ( new \App\Repositories\AppRepository($appid) )->getconfig();

    // $ret = $this->gettoken( $request->get('code'), $config['app_id'], $config['secret'] );
    // if( !$ret ){
      $appconfig = [
          'app_id' => $config['app_id'],
          'secret' => $config['secret'],
      
          // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
          'response_type' => 'array',
      
          'log' => [
            'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
            'channels' => [
                // 测试环境
                'dev' => [
                    'driver' => 'single',
                    'path' => '/tmp/easywechat.log',
                    'level' => 'debug',
                ],
                // 生产环境
                'prod' => [
                    'driver' => 'daily',
                    'path' => '/tmp/easywechat.log',
                    'level' => 'info',
                ],
            ],
        ],
      ];
      $app = Factory::miniProgram($appconfig);
      $ret = $app->auth->session($request->get('code'));
    // }


    $fromid = intval($request->server('HTTP_FROMID'));
    if(!$fromid) $fromid = intval($request->server('HTTP_FORMID'));

    if( array_get($ret, 'errcode') ){
      return response()->json($ret);
    }
    
    $openid = array_get($ret,'openid');
    $session_key = array_get($ret,'session_key');

    $fan = Fan::firstOrNew(['openid'=>$openid, 'appid'=>$appid]);
    if ( !$fan->id ) { // 新访客
      // 如果该用户不存在则将其保存到 users 表
      $fan->session_key = $session_key;
      $fan->fromid = $fromid;
      $fan->save();
      //  如果没有fromid 使用默认 fromid  (自然流量提供给指定id用户)
      $fromid = $fromid ? $fromid : intval($config['default_fromid']); // config('point.default_fromid'); 
      if( $fromid && config('point.channel_status') ){
        $fromuser = Fan::find($fromid);
        if($fromuser->id && !$fromuser->lock_at ){
          $_task = $fromuser->todaytask();
          if($_task->todayInterviewAdd()){
            $fromuser->changePoint($_task->todayInterviewAction(),'邀请新用户');
            $_task->save();
          }
        }
      }
    }elseif( $fan->id && config('point.share_action') ){ // 分享(老用户)访问奖励
      $check_vistor = Visitor::firstOrNew(['user_id'=>$fan->id, 'appid'=>$appid, 'did'=>date('Ymd')]);
      if( !$check_vistor->id ){ // 今天没有记录这个访客已经访问
        if( $fromid && $fan->id != $fromid && config('point.channel_status')  ){
          $fromuser = Fan::find($fromid);
          if( $fromuser->id && !$fromuser->lock_at ){
            $_task = $fromuser->todaytask();
            if($_task->todayShareAdd()){
              $fromuser->changePoint($_task->todayShareAction(),'渠道访问');
              $_task->save();
            }
          }
        }
      }
    }
    
    if( $fan->session_key !== $session_key ){
      $fan->session_key = $session_key;
      $fan->save();
    }
    $vistor = Visitor::firstOrNew(['user_id'=>$fan->id, 'appid'=>$appid, 'did'=>date('Ymd')]);
    if( $vistor->id ){ // 如果今天已经记录了，创建一个新的 负数did的
      Visitor::firstOrCreate([ 'user_id'=>$fan->id, 'appid'=>$appid, 'did'=>-1*date('Ymd'), 'fromid'=>$fromid, 'scene'=>$scene ]);
    }else{
      $vistor->fromid = $fromid;
      $vistor->scene = $scene;
      $vistor->save();
    }
    $token = JWTAuth::fromUser($fan);
    
    $success['token'] =  $token;
    $success['uid'] =  $fan->id;
    $success['show_login'] = $fan->name && $fan->avatar?false:true; // 提醒登录
    $success['show_sign'] =  $fan->sign_at == date('Ymd')?true:false;  // 提醒今天未签到
    $success['show_rewarded'] =  $fan->rewarded_at == date('Ymd')?true:false; // 提醒今天未激励
    $success['index_share_title'] =  $config['index_share_title'];
    $success['index_share_cover'] =  $config['index_share_cover'];
    $success['topic_share_title'] =  $config['topic_share_title'];
    $success['topic_share_cover'] =  $config['topic_share_cover'];
    $success['default_search'] =  $config['default_search'];
    $success['reward_adid'] =  $config['reward_adid'];
    $success['banner_adid'] =  $config['banner_adid'];
    $success['sign_action'] = $config['point_sign_action'];
    $success['reward_action'] = $config['point_reward_action'];
    // todo 获取分享页数据

    return response()->json($success);
  }

  public function GetShareConfig(){

  }
  
  public function asyncuserdata(Request $request){
    $appid = intval($request->get('appid'));
    $config = ( new \App\Repositories\AppRepository($appid) )->getconfig();
    $appconfig = [
      'app_id' => $config['app_id'],
      'secret' => $config['secret'],
  
      // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
      'response_type' => 'array',
  
      'log' => [
        'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path' => '/tmp/easywechat.log',
                'level' => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'daily',
                'path' => '/tmp/easywechat.log',
                'level' => 'info',
            ],
        ],
      ],
    ];
    $app = Factory::miniProgram($appconfig);
    $user = $request->user();
    $decryptedData = $app->encryptor->decryptData($user->session_key, $request->post('iv'), $request->post('ed'));
    $user->name = $decryptedData['nickName'];   
    $user->gender = $decryptedData['gender'];
    $user->city = $decryptedData['city'];
    $user->avatar = $decryptedData['avatarUrl'];
    $user->save();
    return response()->json(['ok']);
  }

  

  public function check(Request $request){
    return response()->json($request->user());
  }

}
