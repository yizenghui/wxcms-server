<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use App\Models\Fan;
use App\Models\Order;
use App\Models\Article;
use App\Models\PointLog;
use App\Models\Visitor;
use Carbon\Carbon;
use Admin;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $content->header('Info box');
        $content->description('Description...');

        $content->row(function ($row) {
            $fan_count = Fan::where('appid', '=', Admin::user()->id)->count();

            $fan_today_add_count = Fan::where('appid', '=', Admin::user()->id)->where('created_at', '>=', Carbon::now()->today())->count();
            $order_count = Order::where('appid', '=', Admin::user()->id)->count();
            $article_count = Article::where('appid', '=', Admin::user()->id)->count();

            $point_total = PointLog::where('appid', '=', Admin::user()->id)->sum('change');
            $today_point_total = PointLog::where('appid', '=', Admin::user()->id)->where('created_at', '>=', Carbon::now()->today())->sum('change');
            // dd($today_point_total);
            
            $yesterday = Carbon::now()->yesterday()->format('Ymd');
            $yesterday_visitor = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', $yesterday)->count();
            $yesterday_visitor_rp = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', -1*$yesterday)->count();

            $today = Carbon::now()->today()->format('Ymd');
            $today_visitor = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', $today)->count();
            $today_visitor_rp = Visitor::where('appid', '=', Admin::user()->id)->where('did', '=', -1*$today)->count();

            $row->column(3, new InfoBox('积分'.' (今天'.$today_point_total.')', 'list', 'red', '/admin/order', $point_total));
            $row->column(3, new InfoBox('粉丝'.' (新增'.$fan_today_add_count.')', 'users', 'aqua', '/admin/fan', $fan_count));
            $row->column(3, new InfoBox('订单', 'shopping-cart', 'green', '/admin/order', $order_count));
            $row->column(3, new InfoBox('文章', 'book', 'yellow', '/admin/article', $article_count));
            $row->column(3, new InfoBox('访客统计'.' (昨天'.$yesterday_visitor.'+'.$yesterday_visitor_rp.')', 'list', 'blue', '/admin/visitor',  $today_visitor.'+'.$today_visitor_rp));

            // $row->column(3, new InfoBox('API Use Total', 'file', 'red', '/admin/api', Admin::user()->total_quota));
        });
        return $content;
    }
}
