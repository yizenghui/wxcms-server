<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\MessageBag;
use Admin;


class OrderController extends Controller
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
            ->header('订单')
            ->description('管理您的订单')
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
        $data = Order::findOrFail($id);
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
        $grid = new Grid(new Order);

        $grid->user_id('UID');
        $grid->model()->where('appid', '=', Admin::user()->id);
        //  $token = Hashids::encode($this->id);
        $grid->id('ID')->display(function($id) {
            return  Hashids::encode($id);
        });
        // $grid->user()->name('名称');
        $grid->name('名称');
        $grid->num('数量');
        $grid->point_total('积分小计');
        $grid->cash_total('现金价值')->display(function ($t) {
            return ($t/100).'元';
        });
        $grid->delivery_at('发货时间');
        $grid->lower_at('失效时间');
        // $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->where(function ($query) {
                $ids = Hashids::decode($this->input);
                if($ids) $query->where('id', '=', $ids[0]);
            }, '密令查询');
            // 在这里添加字段过滤器
            $filter->like('name', '名称');
            $filter->between('delivery_at', '发货时间')->datetime();
            $filter->between('lower_at', '失效时间')->datetime();
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
        $show = new Show(Order::where('appid', '=', Admin::user()->id)->findOrFail($id));

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
        $form = new Form(new Order);

        $form->display('id','ID');
        $form->display('user_id','UID');
                
    
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
        $form->datetime('delivery_at','发货时间');
        $form->simplemde('prove','发货证明')->help('上传图片到图床<a target="_blank" href="https://sm.ms/">sm.ms</a>复制Markdown语法标签');

        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
