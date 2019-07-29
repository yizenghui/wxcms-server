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

    public function code(Request $request){
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || !$refresh_token) return redirect('wxoauth'); //没有app_id或refresh_token,都去跑授权
        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);
        // $code =  $miniProgram->code; // 代码管理
        return view('code',[
            'app'=>$app,
            'qrcode'=> '/wxoauth/getQrCode?appid='.$appid,
            'release_version'=>$app->release_version, //发布版本
            'current_version'=>$app->current_version, //用户提供体验版本
            'master_version' =>config('point.mini_program_version'), //当前主版本
        ]);
    }

    /**
     * 获取小程序配置参数
     */
    private function ext_json($app){
        return [
            "extEnable"=>true,
            "extAppid"=>$app->app_id,
            "directCommit"=>false,
            "ext"=> [
                "api_token"=> Hashids::encode($app->id,date('Ymd')),
                "app_name"=> $app->app_name,
                "version"=> config('point.mini_program_version'),
                "base_url"=> "https://readfollow.com/api/v1"
            ]
        ];
    }

    public function commitCode(Request $request){ // 提交代码
        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || !$refresh_token) return redirect('wxoauth'); //没有app_id或refresh_token,都去跑授权
        // $app_id = "wxa94ddd94358b2d1d";$refresh_token = "refreshtoken@@@mDipKwa1XOMderGFqEovsVpMuWmY6u0iAf_Ef9Y0in8";
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $c = $openPlatform->code_template; // 模板
        $l = $c->list(); // 模板列表
        $template_id = $l["template_list"][0]["template_id"]; //模板列表顺位第一模板id
        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);

        $version = config('point.mini_program_version');
        $version_desc = config('point.mini_program_version_desc');
        $ret = $miniProgram->code->commit($template_id, json_encode($this->ext_json($app)), $version, $version_desc);
        if( $ret["errcode"] ){
            return $ret["errmsg"];
        }else{
            $app->current_version = $version; //当前提交版本
            $app->save();
        }
        // return $ret;
        $qrcode = '/wxoauth/getQrCode?appid='.$appid;
        // 跳转去获取体验版二维码
        $cate = $miniProgram->code->getCategory();
        $categories = $cate["category_list"];
        return view('commit',compact('qrcode','categories'));
    }

    // 为客户设置分类(暂不支持)
    public function settingCategory(Request $request){ // 设置分类
        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || !$refresh_token) return redirect('wxoauth'); //没有app_id或refresh_token,都去跑授权
        // $app_id = "wxa94ddd94358b2d1d";$refresh_token = "refreshtoken@@@CMuaHhGhW0T0cKjmFLGvRyrGknttrKA4mXWlnD-45Jk";
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $all_categories = $miniProgram->setting->getAllCategories();
        // todo 检查用户类型及设置相应分类 "errcode":41033 非第三方快速创建的小程序，获取、设置用户帐号私密信息都容易出这个错，暂时无解，以后再尝试解决
        $cate = $miniProgram->code->getCategory();
        $categories = $cate["category_list"];
        return view('commit',compact('qrcode','categories'));
    }

    // 获取体验二维码
    public function getQrCode(Request $request){
        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || !$refresh_token) return 'falt'; //没有app_id或refresh_token
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台
        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token);
        return $miniProgram->code->getQrCode("/pages/index/index");
    }

    // 提交审核
    public function submitAudit(Request $request){
        // $first_id = $request->get("first_id");
        // $second_id = $request->get("second_id");

        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || !$refresh_token) return redirect('wxoauth'); //没有app_id或refresh_token,都去跑授权
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台

        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token); //小程序
        $cate = $miniProgram->code->getCategory();
        $category_list = $cate["category_list"];

        $first_id = 0;
        $second_id = 0;
        $first_class = "";
        $second_class = "";
        // 首次选择 (企业小程序) todo 有空去问问有企业资质的人，这个参数是多少
        foreach ($category_list as $key => $value) {
            if($value["first_id"] == 8 && $value["second_id"] == 578){ // 17 + ?
                $first_id = 8;
                $second_id = 578;
                $first_class = $value["first_class"];
                $second_class = $value["second_class"];
                break;
            }
        }
        
        if(!$first_id && !$second_id){
            // 次选 (个人小程序)
            foreach ($category_list as $key => $value) {
                if($value["first_id"] == 8 && $value["second_id"] == 578){
                    $first_id = 8;
                    $second_id = 578;
                    $first_class = $value["first_class"];
                    $second_class = $value["second_class"];
                    break;
                }
            }
            //8 578 
        }

        if(!$first_id && !$second_id){
            // 补底默认选择第一个
            $first_id = $category_list[0]["first_id"];
            $second_id = $category_list[0]["second_id"];
            $first_class = $value["first_class"];
            $second_class = $value["second_class"];
        }


        // https://developers.weixin.qq.com/miniprogram/product/ 提交审核前提醒必须了解相关运营要求，以免产生不必要的损失又来找我们
        $itemList = ["item_list"=>[
            [
                "address"=>"pages/index/index",
                "tag"=>"文章 阅读",
                "first_class"=>  $first_class,
                "second_class"=> $second_class,
                "first_id"=>$first_id,
                "second_id"=>$second_id,
                "title"=> "首页"
            ],
            [
                "address"=>"pages/jump/index",
                "tag"=>"跳转 wecontr",
                "first_class"=>  $first_class,
                "second_class"=> $second_class,
                "first_id"=>$first_id,
                "second_id"=>$second_id,
                "title"=> "中转页"
            ],
        ]];
        
        $miniProgram->code->submitAudit($itemList); // submitAudit
        return view('audit');
    }

    public function releaseCode(Request $request){
        // $first_id = $request->get("first_id");
        // $second_id = $request->get("second_id");

        $appid = $request->get('appid');
        $app = App::find(intval($appid));
        $app_id = $app->app_id;//$request->get('app_id');
        $refresh_token = $app->refresh_token; //$request->get('refresh_token');
        if(!$app_id || !$refresh_token) return redirect('wxoauth'); //没有app_id或refresh_token,都去跑授权
        $openPlatform = \EasyWeChat::openPlatform(); // 开放平台

        $miniProgram = $openPlatform->miniProgram($app_id, $refresh_token); //小程序
        
        // dd($miniProgram->code->release());
        return view('welcome');
    }
}
