<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Models\Boss;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;

class GoodsController extends Controller
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
            ->header('商品')
            ->description('积分商城可兑换商品')
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
        $data = Goods::findOrFail($id);
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
        $grid = new Grid(new Goods);
        $grid->model()->where('appid', '=', Admin::user()->id);
        $grid->id('ID');
        $grid->name('商品名称');
        $grid->cash_value('现金价值(单位分)');
        $grid->point('兑换需要积分');
        $grid->stock('库存量');
        $grid->out('出货量');
        $grid->lower_at('下架时间');
        $grid->invalid_at('兑换卷失效时间');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(Goods::where('appid', '=', Admin::user()->id)->findOrFail($id));
        $show->id('ID');
        $show->name('商品名称');
        $show->cash_value('现金价值(单位分)');
        $show->point('兑换需要积分');
        $show->stock('库存量');
        $show->out('出货量');
        $show->lower_at('下架时间');
        $show->invalid_at('兑换卷失效时间');
        $show->state('状态')->as(function($v){
            $state_arr = [
                0=>'隐藏',
                1=>'展示',
            ];
            if(isset($state_arr[$v])) return $state_arr[$v];
            return $v;
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
        $form = new Form(new Goods);

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
        $form->select('boss_id','赞助商')->options(Boss::all()->pluck('name', 'id'))->rules('required')->required();
        $form->text('name','商品名称')->rules('required')->required();
        $form->number('cash_value','现金价值(单位分)')->rules('required')->required()->default(0);
        $form->number('point','兑换需要积分')->rules('required')->required()->default(0);
        $form->cropper('cover','封面图');
        $form->textarea('intro','简介');
        $form->number('stock','库存量')->default(0);
        $form->number('out','出货量')->default(0);
        $form->text('tag','标签')->rules('max:6');
        $form->select('tag_style','标签样式')->options([''=>'无','red'=>'红','blue'=>'蓝','grey'=>'灰']);
        $form->datetime('lower_at','下架时间')->default(date('Y-m-d 23:59:59',time()+86400*7));
        $form->datetime('invalid_at','兑换卷失效时间')->default(date('Y-m-d 23:59:59',time()+86400*37));
        $form->simplemde('body','商品描述正文');

        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
