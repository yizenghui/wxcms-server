<?php

namespace App\Http\Controllers\Api;

use App\Models\Fan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat;
use Auth;
use JWTAuth;

class AuthController extends Controller
{
  
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['token']]);
  }
  //
  public function token(Request $request){
    $app = EasyWeChat::miniProgram(); // 小程序
    $pid = intval($request->get('pid'));
    $ret = $app->auth->session($request->get('code'));
    if( array_get($ret,'errcode') ){
      // todo 出错处理
      return response()->json($ret);
    }
    // https://laravelacademy.org/post/8900.html
    $openid = array_get($ret,'openid');
    $session_key = array_get($ret,'session_key');

    // return response()->json($ret);
    $fan = Fan::where( 'openid', '=', $openid )
        ->first();
    if ($fan == null) {
      // 如果该用户不存在则将其保存到 users 表
      $newFan = new Fan();
      $newFan->openid      = $openid;
      $newFan->session_key = $session_key;
      $newFan->pid = $pid;
      $newFan->save();
      $fan = $newFan;
    }
    if( $fan->session_key !== $session_key ){
      $fan->session_key = $session_key;
      $fan->save();
    }
    // Auth::login( $fan );
    // $data = $fan->toArray();
    // $data['password'] = '12345';

    $token = JWTAuth::fromUser($fan);
    // $success['token'] = auth('api')->attempt($data);

    // $token = auth('api')->attempt($fan->toArray());
    // $success['token'] =  JWTAuth::attempt($fan->toArray());
    $success['token'] =  $token;
    return response()->json($success);
  }

  
  public function asyncuserdata(Request $request){
    $app = EasyWeChat::miniProgram(); // 小程序
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
