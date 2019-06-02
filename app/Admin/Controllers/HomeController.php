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
use Carbon\Carbon;
use Admin;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $content->header('Info box');
        $content->description('Description...');

        $content->row(function ($row) {
            $fan_count = Fan::where('tenancy_id', '=', Admin::user()->id)->count();

            $fan_today_add_count = Fan::where('tenancy_id', '=', Admin::user()->id)->where('created_at', '>=', Carbon::now()->today())->count();
            $order_count = Order::where('tenancy_id', '=', Admin::user()->id)->count();
            $article_count = Article::where('tenancy_id', '=', Admin::user()->id)->count();

            $point_total = PointLog::where('tenancy_id', '=', Admin::user()->id)->sum('change');
            $today_point_total = PointLog::where('tenancy_id', '=', Admin::user()->id)->where('created_at', '>=', Carbon::now()->today())->sum('change');
            // dd($today_point_total);
            

            $row->column(3, new InfoBox('积分'.' (今天'.$today_point_total.')', 'list', 'red', '/tenancy/order', $point_total));
            $row->column(3, new InfoBox('粉丝'.' (新增'.$fan_today_add_count.')', 'users', 'aqua', '/tenancy/fan', $fan_count));
            $row->column(3, new InfoBox('订单', 'shopping-cart', 'green', '/tenancy/order', $order_count));
            $row->column(3, new InfoBox('文章', 'book', 'yellow', '/tenancy/article', $article_count));
            // $row->column(3, new InfoBox('API Use Total', 'file', 'red', '/tenancy/api', Admin::user()->total_quota));
        });
        return $content;
    }
}
