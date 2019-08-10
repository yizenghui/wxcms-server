<?php

namespace App\Admin\Controllers;

use App\Models\Fan;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;

class FanController extends Controller
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
            ->header('粉丝')
            ->description('管理您的粉丝')
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
        // 检查是否具有修改该数据的权限
        $data = Fan::findOrFail($id);
        // 不是超级管理员或者不是自己的资源
        if(!Admin::user()->isAdministrator() && $data->appid!=Admin::user()->id){
            return $content->withError('出错了', '无权查看该资源');
        }
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
        $grid = new Grid(new Fan);
        // $grid->model()->with('fromuser');
        $grid->model()->where('appid', '=', Admin::user()->id);
        $grid->id('ID')->sortable();
        // $grid->wxid('微信号')->editable();
        $grid->name('昵称')->limit(10);
        // $grid->avatar('头像')->image('',32,32);
        // $grid->fromuser('推荐人')->name()->limit(10);
        $grid->point('剩余积分')->sortable();
        $grid->total_point('总积分')->sortable();
        $grid->fromid('fromid')->sortable();
        // $grid->current_point('可用积分')->sortable();
        $grid->created_at('Created at')->sortable();
        $grid->updated_at('Updated at')->sortable();


        $grid->disableCreateButton();
        
        $grid->filter(function($filter){

            $filter->column(1/2, function ($filter) {
                $filter->like('name', '名称');
                $filter->like('city', '城市');
                $filter->group('fromid', '推荐人id', function ($group) {
                    $group->equal('等于');
                    $group->gt('大于');
                    $group->lt('小于');
                    $group->nlt('不小于');
                    $group->ngt('不大于');
                });

            });
            
            $filter->column(1/2, function ($filter) {
                $filter->in('gender','性别')->checkbox([ '0' => '未知', '1' => '男', '2' => '女']);
                $filter->between('lock_at', '锁定时间')->datetime();
                $filter->between('created_at', '创建时间')->datetime();
                $filter->between('updated_at', '更新时间')->datetime();
            });
            
            
        
        });
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
        $show = new Show(Fan::where('appid', '=', Admin::user()->id)->findOrFail($id));
        $show->id('ID');
        $show->openid('OpenID');
        $show->avatar('头像')->image();
        $show->name('名称');
        $show->lock_at('锁定用户');
        $show->current_point('当前积分');
        $show->remarks('备注');
        
        $show->comments('Ta的评论', function ($comment) {
            $comment->disableCreateButton();
            $comment->resource('/admin/comment');
            $comment->id()->sortable();
            $approve_arr = [0=>'待审核',1=>'审核通过',-1=>'审核不通过'];
            $comment->approve('状态')->select($approve_arr);
            $comment->commentable()->title('文章');
            $comment->comment('评论');
            $comment->filter(function ($filter) {
                $filter->like('comment','评论');
            });
        });

        
        $show->rewardlogs('Ta的激励文章记录', function ($readlog) {
            $readlog->id();
            $readlog->title();
            $readlog->disableCreateButton();
            $readlog->disableExport();
            $readlog->disableRowSelector();
            $readlog->disableActions();
        });
        $show->readlogs('Ta的阅读记录', function ($readlog) {
            $readlog->id();
            $readlog->title();
            $readlog->disableCreateButton();
            $readlog->disableExport();
            $readlog->disableRowSelector();
            $readlog->disableActions();
        });
        $show->likelogs('Ta的点赞记录', function ($readlog) {
            $readlog->id();
            $readlog->title();
            $readlog->disableCreateButton();
            $readlog->disableExport();
            $readlog->disableRowSelector();
            $readlog->disableActions();
        });
        $show->orders('Ta的订单记录', function ($order) {
            $order->id();
            $order->name('名称');
            $order->point_total('积分小计');
            $order->cash_total('现金价值')->display(function ($t) {
                return ($t/100).'元';
            });
            $order->delivery_at('发货时间');
            $order->lower_at('失效时间');
            $order->created_at();
            $order->filter(function ($filter) {
                $filter->like('intro');
            });
            $order->disableCreateButton();
            $order->disableExport();
            $order->disableRowSelector();
            $order->disableActions();
        });

        $show->pointlogs('Ta的积分记录', function ($pointlogs) {
            $pointlogs->id();
            $pointlogs->change();
            $pointlogs->intro()->limit(10);
            $pointlogs->created_at();
            $pointlogs->filter(function ($filter) {
                $filter->like('intro');
            });
            $pointlogs->disableCreateButton();
            $pointlogs->disableExport();
            $pointlogs->disableRowSelector();
            $pointlogs->disableActions();
        });
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
        $form = new Form(new Fan);

        $form->display('ID');
        // 抛出错误信息
        $form->saving(function ($form) {

            if($form->model()->id){
                if( $form->model()->appid !=Admin::user()->id ){
                    $error = new MessageBag([
                        'title'   => '出错了',
                        'message' => '数据异常，请重新编辑！',
                    ]);
                    return back()->with(compact('error'));
                }
            }else{
                if(!$form->appid || $form->appid!=Admin::user()->id){
                    $error = new MessageBag([
                        'title'   => '出错了',
                        'message' => '数据异常，请重新编辑！',
                    ]);
                    return back()->with(compact('error'));
                }
            }
        });
        
        $form->hidden('appid')->default(Admin::user()->id);
        $form->text('name','昵称');
        $form->text('wxid','微信号');
        $form->number('point','剩余积分');
        $form->number('current_point','可用积分');
        $form->number('total_point','总积分');
        $form->datetime('lock_at','锁定用户');
        $states = [
            'on'  => ['value' => 1, 'text' => '展示', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '隐藏', 'color' => 'danger'],
        ];
        $form->switch('channel_status', '渠道奖励')->states($states)->default(1)->help('在积分攻略展示渠道信息');
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
