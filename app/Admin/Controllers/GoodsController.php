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
            ->header('Index')
            ->description('description')
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
        $grid = new Grid(new Goods);

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
        $show = new Show(Goods::findOrFail($id));
        $show->id('ID');
        $show->name('商品名称');
        $show->cash_value('现金价值(单位分)');
        $show->point('兑换需要积分');
        $show->stock('库存量');
        $show->out('出货量');
        $show->lower_at('下架时间');
        $show->invalid_at('兑换卷失效时间');
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
        
        $form->select('boss_id','服务商')->options(Boss::all()->pluck('name', 'id'))->rules('required')->required();
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
