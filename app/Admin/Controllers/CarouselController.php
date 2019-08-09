<?php

namespace App\Admin\Controllers;

use App\Models\Carousel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;

class CarouselController extends Controller
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
            ->header('轮播')
            ->description('轮播内容管理')
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
        $data = Carousel::findOrFail($id);
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
        $grid = new Grid(new Carousel);
        $grid->model()->where('appid', '=', Admin::user()->id);

        $grid->id('ID'); 
        
        $grid->name('名称');
        $grid->position('位置')->display(function ($v) {
            $postion_arr = [
                1=>'首页',
                2=>'用户页',
                3=>'积分商城',
            ];
            return $postion_arr[$v];
        });
        $grid->genre('类型')->display(function ($v) {
            $genre_arr = [
                0=>'仅展示',
                1=>'链接',
                2=>'海报',
                3=>'跳小程序',
            ];
            return $genre_arr[$v];
        });
        $grid->state('状态')->display(function ($state) {
            return $state ? '启用' : '暂停';
        });
        $grid->load_num('加载次数');
        $grid->click_num('点击次数');
        $grid->start_at('开始时间');
        $grid->end_at('结束时间');

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
        $show = new Show(Carousel::findOrFail($id));

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
        $form = new Form(new Carousel);

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
        $form->text('name','标题')->rules('required')->required();
        $postion_arr = [
            1=>'首页',
            2=>'用户页',
            3=>'积分商城',
        ];
        $form->select('position','展现位置')->options($postion_arr)->rules('required')->required();
       
        $genre_arr = [
            0=>'仅展示',
            1=>'链接',
            2=>'海报',
            3=>'跳小程序',
        ];
        $form->select('genre','展现类型')->options($genre_arr)->rules('required')->required();
       
        $form->cropper('cover','图片')->rules('required');
        $form->text('url','链接地址');
        $form->text('gotoapp','跳转小程序');
        $form->cropper('poster','海报图片');
        $form->datetime('start_at','开始时间');
        $form->datetime('end_at','结束时间');
        $form->slider('priority','优先度')->options(['max' => 10, 'min' => 1, 'step' => 1, 'postfix' => '']);

        $states = [
            'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '暂停', 'color' => 'danger'],
        ];
        
        $form->switch('state', '状态')->states($states)->default(1);

        $form->display('load_num','加载次数');
        $form->display('click_num','点击次数');

        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
