<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ConfigController extends Controller
{
    public function index(){
        return [

        ];
    }

    /**
     * 用户首页配置
     */
    public function userhome(){
        return [
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }

    /**
     * 用户后台组队页
     */
    public function teamhome(){
        return [
            'rule'=>[

            ],
            'reward'=>[
                
            ],
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }
    /**
     * 分享组队加入页
     */
    public function teamjoin(){
        return [
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }
    /**
     * 主页 首页推荐页
     */
    public function home(){
        return [
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }

    /**
     * 专题主页 所有专题
     */
    public function topichome(){
        return [
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }

    /**
     * 专题列表页
     */
    public function topiclist(){
        return [
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }

    /**
     * 文章详细页
     */
    public function articleinfo(){
        return [
            'swipers'=>[
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-1.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-2.jpg','wxto'=>''],
                ['name'=>'','cover'=>'https://image.weilanwl.com/img/4x3-3.jpg','wxto'=>''],
            ],
        ];
    }



}
