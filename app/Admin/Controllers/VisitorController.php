<?php

namespace App\Admin\Controllers;

use App\Models\Visitor;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Admin;

class VisitorController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('访客')
            ->description('访客记录查询')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Visitor);
        
        // $grid->model()->ordered();
        $grid->model()->where('appid', '=', Admin::user()->id)->orderBy('id', 'DESC');

        $grid->id('ID')->sortable();
        $grid->fromid('FromID')->sortable();
        $grid->user_id('UID')->sortable();
        $grid->did('DID')->sortable();
        // 1001
        // $grid->scene('scene');
        
        $grid->scene()->display(function ($scene) {
            $scene_arr = [
                -1=>'-1',
                0=>'未知',
                1001=>'发现栏小程序主入口，「最近使用」列表（基础库2.2.4版本起包含「我的小程序」列表）',
                1005=>'微信首页顶部搜索框的搜索结果页',
                1006=>'发现栏小程序主入口搜索框的搜索结果页',
                1007=>'单人聊天会话中的小程序消息卡片',
                1008=>'群聊会话中的小程序消息卡片',
                1011=>'扫描二维码',
                1012=>'长按图片识别二维码',
                1013=>'扫描手机相册中选取的二维码',
                1014=>'小程序模板消息',
                1017=>'前往小程序体验版的入口页',
                1019=>'微信钱包（微信客户端7.0.0版本改为支付入口）',
                1020=>'公众号 profile 页相关小程序列表',
                1022=>'聊天顶部置顶小程序入口（微信客户端6.6.1版本起废弃）',
                1023=>'安卓系统桌面图标',
                1024=>'小程序 profile 页',
                1025=>'扫描一维码',
                1026=>'发现栏小程序主入口，「附近的小程序」列表',
                1027=>'微信首页顶部搜索框搜索结果页「使用过的小程序」列表',
                1028=>'我的卡包',
                1029=>'小程序中的卡券详情页',
                1030=>'自动化测试下打开小程序',
                1031=>'长按图片识别一维码',
                1032=>'扫描手机相册中选取的一维码',
                1034=>'微信支付完成页',
                1035=>'公众号自定义菜单',
                1036=>'App 分享消息卡片',
                1037=>'小程序打开小程序',
                1038=>'从另一个小程序返回',
                1039=>'摇电视',
                1042=>'添加好友搜索框的搜索结果页',
                1043=>'公众号模板消息',
                1044=>'带 shareTicket 的小程序消息卡片',
                1045=>'朋友圈广告',
                1046=>'朋友圈广告详情页',
                1047=>'扫描小程序码',
                1048=>'长按图片识别小程序码',
                1049=>'扫描手机相册中选取的小程序码',
                1052=>'卡券的适用门店列表',
                1053=>'搜一搜的结果页',
                1054=>'顶部搜索框小程序快捷入口（微信客户端版本6.7.4起废弃）',
                1056=>'聊天顶部音乐播放器右上角菜单',
                1057=>'钱包中的银行卡详情页',
                1058=>'公众号文章',
                1059=>'体验版小程序绑定邀请页',
                1064=>'微信首页连Wi-Fi状态栏',
                1067=>'公众号文章广告',
                1068=>'附近小程序列表广告（已废弃）',
                1069=>'移动应用',
                1071=>'钱包中的银行卡列表页',
                1072=>'二维码收款页面',
                1073=>'客服消息列表下发的小程序消息卡片',
                1074=>'公众号会话下发的小程序消息卡片',
                1077=>'摇周边',
                1078=>'微信连Wi-Fi成功提示页',
                1079=>'微信游戏中心',
                1081=>'客服消息下发的文字链',
                1082=>'公众号会话下发的文字链',
                1084=>'朋友圈广告原生页',
                1089=>'微信聊天主界面下拉，「最近使用」栏（基础库2.2.4版本起包含「我的小程序」栏）',
                1090=>'长按小程序右上角菜单唤出最近使用历史',
                1091=>'公众号文章商品卡片',
                1092=>'城市服务入口',
                1095=>'小程序广告组件',
                1096=>'聊天记录',
                1097=>'微信支付签约页',
                1099=>'页面内嵌插件',
                1102=>'公众号 profile 页服务预览',
                1103=>'发现栏小程序主入口，「我的小程序」列表（基础库2.2.4版本起废弃）',
                1104=>'微信聊天主界面下拉，「我的小程序」栏（基础库2.2.4版本起废弃）',
                1124=>'扫“一物一码”打开小程序',
                1125=>'长按图片识别“一物一码”',
                1126=>'扫描手机相册中选取的“一物一码”',
                1129=>'微信爬虫访问'
            ];
            if(isset($scene_arr[$scene])) return $scene_arr[$scene];
            return $scene;
        });

        $grid->created_at('Created at')->sortable();
        
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->filter( function($filter){
            $filter->disableIdFilter();
            $filter->equal('user_id', 'UID');
            $filter->equal('fromid', 'FromID');
            $filter->equal('scene', 'scene');
            $filter->between('did', 'DID');
        } );
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Visitor::where('appid', '=', Admin::user()->id)->findOrFail($id));

        $show->id('ID');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Visitor);

        $form->display('ID');
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
