<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\App;
use Vinkla\Hashids\Facades\Hashids;
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
        $app_id = $data['authorization_info']['authorizer_appid'];
        $refresh_token = $data['authorization_info']['authorizer_refresh_token'];
        $app = App::firstOrNew(['app_id'=>$app_id]); // 这里是简版的，搬到后台应该加用户登录状态判断防止越权
        if( $app->id ){ // 正常的
            $app->refresh_token = $refresh_token;
            //记录refresh_token到数据库
            $app->save();
            return redirect('wxoauth/commitCode?appid='.$app->id);
        }else{ // 不正常的
            return "出错了，无法匹配到相应的AppID";
        }
    }

    public function getTemplateList(Request $request){
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        
        dd($miniProgram->code);
        return view('welcome');
    }


    public function commitCode(Request $request){ // 提交代码
        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || $refresh_token) return redirect('wxoauth'); //没有app_id或refresh_token,都去跑授权
        // $app_id = "wxa94ddd94358b2d1d";$refresh_token = "refreshtoken@@@NlYaCEWLcSwu5VpBCEf6b9b_Y57sbo7lL2Qm1x1T79M";
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $c = $openPlatform->code_template; // 模板
        $l = $c->list(); // 模板列表
        $template_id = $l["template_list"][0]["template_id"]; //模板列表顺位第一模板id
        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);

        $version = config('point.mini_program_version');
        $version_desc = config('point.mini_program_version_desc');
        $ret = $miniProgram->code->commit($template_id,json_encode(['api_token'=>Hashids::encode($app->id,date('Ymd')), 'app_name'=>$app->app_name]), $version, $version_desc);
        if(1){ // todo 提交代码失败
            $app->current_version = $version; //当前提交版本
            $app->save();
        }
        // return $ret;
        $qrcode = 'wxoauth/getQrCode?appid='.$appid;
        // 跳转去获取体验版二维码
        return view('welcome',compact('qrcode'));
    }


    public function getQrCode(Request $request){ // 提交代码
        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || $refresh_token) return 'falt'; //没有app_id或refresh_token
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);
        return $miniProgram->code->getQrCode("/pages/index/index");
    }

    public function postCode2(Request $request){
        $app_id = $request->get('app_id');
        $refresh_token = $request->get('refresh_token');
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台

        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token); //小程序
        dd($miniProgram->code);
        return view('welcome');
    }
}
