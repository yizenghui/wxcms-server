<?php

namespace App\Http\Controllers\Api;

use App\Models\Fan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat;
use Auth;
use JWTAuth;
use EasyWeChat\Factory;
use Vinkla\Hashids\Facades\Hashids;

class QrcodeController extends Controller
{
  
  public function __construct()
  {
    // $this->middleware('auth:api', ['except' => ['token']]);
  }

  public function jump($token, Request $request){
    $data = Hashids::decode($token);
    $appid = $data[0];
    $fromid = $data[1];
    $articleid = $data[2];

    $app = ( new \App\Repositories\AppRepository($appid) )->getminiapp();


    $adpage = '/pages/index/index?fromid='.$fromid;

    if($app['jump_adpage']){
      $adpage = strtr($app['jump_adpage'],['{$fromid}'=>$fromid]);
    }

    $adpagebackground = $app['jump_background']?$app['jump_background']:'https://wx1.wechatrank.com/base64img/20190528131816.jpeg';

    $ret = [
      'adpage'  => $adpage,
      'page'  => '/pages/index/index?fromid='.$fromid,
      'background'=> $adpagebackground,
    ];

    if($articleid){
      $ret['page'] = '/pages/article/info?fromid='.$fromid.'&id='.$articleid;
    }
    
    return response()->json( $ret );
  }
  
  public function article($token, Request $request){
    $data = Hashids::decode($token);
    $appid = $data[0];
    $config = ( new \App\Repositories\AppRepository($appid) )->getconfig();    
    if( $config['secret'] ){ //如果没有 secret 尝试使用 refresh_token
      $app = Factory::miniProgram($config);
    }else{
      $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
      $app = $openPlatform->miniProgram($config['app_id'], $config['refresh_token']);
    }

    $response = $app->app_code->getUnlimit($token, [
      'page'  => 'pages/jump/index',
      'width' => 160,
    ]);
    return response($response, 200, [
      'Content-Type' => 'image/png',
    ]);
  }

  public function check(Request $request){
    return response()->json($request->user());
  }

}
