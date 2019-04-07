<?php

namespace App\Admin\Controllers;

use App\Models\Fan;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
        $grid = new Grid(new Fan);
        $grid->id('ID')->sortable();
        $grid->name('昵称');
        $grid->formid('推荐人id')->sortable();
        $grid->point('剩余积分')->sortable();
        $grid->total_point('总积分')->sortable();
        // $grid->current_point('可用积分')->sortable();
        $grid->created_at('Created at')->sortable();
        $grid->updated_at('Updated at')->sortable();


        
        $grid->filter(function($filter){

            $filter->column(1/2, function ($filter) {
                $filter->like('name', '名称');
                $filter->like('city', '城市');
                $filter->group('formid', '推荐人id', function ($group) {
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
        $show = new Show(Fan::findOrFail($id));

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
        $form = new Form(new Fan);

        $form->display('ID');
        $form->text('name','昵称');
        $form->number('point','剩余积分');
        $form->number('current_point','可用积分');
        $form->number('total_point','总积分');
        $form->datetime('lock_at','锁定用户');
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
