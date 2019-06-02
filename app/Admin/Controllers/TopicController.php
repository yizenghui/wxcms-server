<?php

namespace App\Admin\Controllers;

use App\Models\Topic;
use App\Models\Share;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;
class TopicController extends Controller
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

        // 检查是否具有修改该数据的权限
        $data = Topic::findOrFail($id);
        // 不是超级管理员或者不是自己的资源
        if(!Admin::user()->isAdministrator() && $data->tenancy_id!=Admin::user()->id){
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
        $grid = new Grid(new Topic);
        $grid->model()->where('tenancy_id', '=', Admin::user()->id);
        $grid->id('ID');
        $grid->name('标题');
        $grid->state('状态')->switch();
        $grid->cover()->display(function ($url) {
            if(!$url) return '';
            // $url = \Storage::disk(config('admin.upload.disk'))->downloadUrl($url,'https');
            $image = "<img style='width: 90px;' src='{$url}'>";
            return $image;
        });
        $grid->order('排序号')->editor();
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
        $show = new Show(Topic::where('tenancy_id', '=', Admin::user()->id)->findOrFail($id));
// dd($show );
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
        $form = new Form(new Topic);

        
        // 抛出错误信息
        $form->saving(function ($form) {

            if($form->model()->id){
                if( $form->model()->tenancy_id !=Admin::user()->id ){
                    $error = new MessageBag([
                        'title'   => '出错了',
                        'message' => '数据异常，请重新编辑！',
                    ]);
                    return back()->with(compact('error'));
                }
            }else{
                if(!$form->tenancy_id || $form->tenancy_id!=Admin::user()->id){
                    $error = new MessageBag([
                        'title'   => '出错了',
                        'message' => '数据异常，请重新编辑！',
                    ]);
                    return back()->with(compact('error'));
                }
            }
        });
        
// dd($form->model());

        $form->hidden('tenancy_id')->default(Admin::user()->id);

        $form->display('ID');
        $form->text('name','标题');
        $form->cropper('cover','封面图');
        $form->textarea('intro','描述');
        $share_arr = collect([0=>'无'])->union(Share::where('tenancy_id', '=', Admin::user()->id)->get()->pluck('name', 'id'))->all();
        $form->select('share_id','自定义分享')->options($share_arr)->default(0)->help('需预设分享策略');
        $form->number('order','排序')->default(0);
        
        $states = [
            'on'  => ['value' => 1, 'text' => '上架', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '下架', 'color' => 'danger'],
        ];
        
        $form->switch('state', '状态')->states($states)->default(1);
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
