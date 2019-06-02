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


    $ret = [
      'adpage'  => '/pages/index/index?fromid='.$fromid,
      'page'  => '/pages/index/index?fromid='.$fromid,
      'background'=>'https://wx1.wechatrank.com/base64img/20190528131816.jpeg',
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
    $app = Factory::miniProgram($config);
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
