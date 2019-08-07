<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Box;
use App\Models\Fan;
use App\Models\Order;
use App\Models\Article;
use App\Models\Author;
use App\Models\Topic;
use App\Models\PointLog;
use App\Models\Visitor;
use Carbon\Carbon;
use Admin;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $content->header('控制台');
        $content->description('信息提示和数据展示');

        $app = Admin::user()->app;
        $vip_status_arr = [0=>'普通用户',1=>'体验VIP用户',2=>'免费VIP用户',30=>'月度VIP',90=>'季度VIP',365=>'包年VIP',999=>'合作伙伴'];

        $article_count = Article::where('appid', '=', Admin::user()->id)->count(); // 文章

        if(!$article_count){ // 没有文章的情况下检查作者和专题
            $author_count = Author::where('appid', '=', Admin::user()->id)->count(); // 作者
            $topic_count = Topic::where('appid', '=', Admin::user()->id)->count(); // 专题
            if( !$article_count && !$topic_count){
                $content->body(new Box('初始数据', '检测到您还未添加初始的数据，您可以<a href="/admin/article/createInitData">立即添加初始数据</a>以便测试。<br>系统添加的初始数据您可以自由修改和删除。'));
            }
        }else{
                
            $content->row(function ($row) use($article_count) {
                $fan_count = Fan::where('appid', '=', Admin::user()->id)->count();

                $fan_today_add_count = Fan::where('appid', '=', Admin::user()->id)->where('created_at', '>=', Carbon::now()->today())->count();
                // $order_count = Order::where('appid', '=', Admin::user()->id)->count();

                $point_total = PointLog::where('appid', '=', Admin::user()->id)->sum('change');
                $today_point_total = PointLog::where('appid', '=', Admin::user()->id)->where('created_at', '>=', Carbon::now()->today())->sum('change');
                // dd($today_point_total);
                
                $yesterday = Carbon::now()->yesterday()->format('Ymd');
                $yesterday_visitor = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', $yesterday)->count();
                $yesterday_visitor_rp = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', -1*$yesterday)->count();

                $today = Carbon::now()->today()->format('Ymd');
                $today_visitor = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', $today)->count();
                $today_visitor_rp = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', -1*$today)->count();

                $row->column(3, new InfoBox('粉丝'.' (新增'.$fan_today_add_count.')', 'users', 'aqua', '/admin/fan', $fan_count));
                // $row->column(3, new InfoBox('订单', 'shopping-cart', 'green', '/admin/order', $order_count));
                $row->column(3, new InfoBox('访客统计'.' (昨天'.$yesterday_visitor.'+'.$yesterday_visitor_rp.')', 'list', 'blue', '/admin/visitor',  $today_visitor.'+'.$today_visitor_rp));
                $row->column(3, new InfoBox('文章', 'book', 'yellow', '/admin/article', $article_count));
                $row->column(3, new InfoBox('积分'.' (今天'.$today_point_total.')', 'list', 'red', '/admin/order', $point_total));

                // $row->column(3, new InfoBox('API Use Total', 'file', 'red', '/admin/api', Admin::user()->total_quota));
            });
        }



        
        if($app){
            
            if($app->isvip){
                $content->body(new Box('尊敬的'.$vip_status_arr[$app->vip_status].'您好', '您的会员截止时间为'.$app->vip_deadline));
            }else{
                $content->body(new Box('尊敬的'.$vip_status_arr[$app->vip_status].'您好','欢迎您的到来。'));
            }

            $code_url = "/wxoauth/code?appid=".$app->tid;
            $content ->row(function (Row $row) use($code_url) {
                $row->column(4, function (Column $column) use($code_url) {
                    $column->append(
                        new Box(
                        '代发布小程序代码', 
                        '填写AppID和AppSecret后<a href="/admin/auth/setting#tab-form-2">前往设置</a>，您可以通过第三方代发布小程序代码的方式快速上传发布小程序代码。<a href="'.$code_url.'">小程序代码管理</a>'
                        )
                    );
                });
                $row->column(4, function (Column $column) {
                    $column->append(new Box(
                        '自行发布小程序代码', 
                        '如果您需要自行修改并发布小程序代码，可以联系售后购买小程序代码，我们会协助您完成修改和发布，您随时都可以获取更新版本，但不支持退款。'
                        ));
                });
                $row->column(4, function (Column $column) {
                    $column->append(new Box(
                        '高级服务', 
                        '我们可为大客户搭建支持更高访问需求的独立服务器和提供订制开发服务，为您量身定制，欢迎咨询免费获取报价。
                        如果您有更深层次的需求，需要购买后端源码二次开发自架服务器，也可与我们联系。'
                        ));
                });
            });
            
        }else{
            $content->body(new Box('接入提醒', '尊敬的用户您好，请先完成小程序配置！<a href="/admin/auth/setting#tab-form-2">前往设置</a>'));
        }
        return $content;
    }
}
