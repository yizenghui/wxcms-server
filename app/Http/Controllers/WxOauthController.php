<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class WxOauthController extends Controller
{

    public function index(Request $request){
        return view('welcome');
    }

    
    public function authorization(){
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        // $openPlatform = Factory::openPlatform(config('wechat.open_platform'));

        $url = $openPlatform->getPreAuthorizationUrl('https://readfollow.com/wxoauth/callback');

        // return redirect($url);
        // authorization
        return "操作流程提醒+<a href='$url'>前往微信公众号平台授权</a>";
    }

    public function callback(){ // 前往微信公众号平台授权跳回来这里了
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $data = $openPlatform->handleAuthorize();
        dump($data);
        dump($data['authorization_info']);
        dd($data['authorization_info']['authorizer_appid']);
        //记录token到数据库
        return $openPlatform->handleAuthorize();

    }

}
