<?php

namespace App\Admin\Controllers;

use App\Models\Ad;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;

class AdController extends Controller
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
            ->header('广告')
            ->description('系统广告管理')
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
        $data = Ad::findOrFail($id);
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
        $grid = new Grid(new Ad);

        $grid->model()->where('appid', '=', Admin::user()->id);
        $grid->id('ID');
        $grid->name('广告名');
        $postion_arr = [
            1=>'首页H1',
            2=>'首页H2',
            3=>'文章A1',
            4=>'文章A2',
            5=>'专题T1',
            6=>'专题T2',
            7=>'专题列表L1',
            8=>'专题列表L2',
            9=>'用户主页U1',
            10=>'用户主页U2',
            11=>'组队页J1',
            12=>'组队页J2',
            13=>'生成海报P1',
            14=>'生成海报P2',
            15=>'积分攻略G1',
            16=>'积分攻略G2',
            // 17=>'积分攻略G2',
            // 18=>'积分攻略G2',
        ];

        $grid->position('位置')->select($postion_arr);

        $genre_arr = [
            0=>'仅展示',
            1=>'图片链接',
            2=>'文字链接',
            // 3=>'图文链接',
            4=>'图片海报',
            5=>'文字海报',
            // 6=>'图文海报',
            7=>'图片跳小程序',
            8=>'文字跳小程序',
            // 9=>'图文跳小程序',
        ];
        $grid->genre('类型')->select($genre_arr);
        $grid->state('状态')->switch();
        // $grid->load_num('加载次数');
        // $grid->click_num('点击次数');
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
        $show = new Show(Ad::findOrFail($id));

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
        $form = new Form(new Ad);

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
            1=>'首页H1',
            2=>'首页H2',
            3=>'文章A1',
            4=>'文章A2',
            5=>'专题T1',
            6=>'专题T2',
            7=>'专题列表L1',
            8=>'专题列表L2',
            9=>'用户主页U1',
            10=>'用户主页U2',
            11=>'组队页J1',
            12=>'组队页J2',
            13=>'生成海报P1',
            14=>'生成海报P2',
            15=>'积分攻略G1',
            16=>'积分攻略G2',
        ];
        $form->select('position','展现位置')->options($postion_arr)->rules('required')->required();
       
      $genre_arr = [
            0=>'仅展示',
            1=>'图片链接',
            2=>'文字链接',
            // 3=>'图文链接',
            4=>'图片海报',
            5=>'文字海报',
            // 6=>'图文海报',
            7=>'图片跳小程序',
            8=>'文字跳小程序',
            // 9=>'图文跳小程序',
        ];
        $form->select('genre','展现类型')->options($genre_arr)->rules('required')->required();
       
        $form->cropper('cover','图片');
        $form->text('text','纯文本');
        $form->text('url','链接地址');
        $form->text('gotoapp','跳转小程序');
        $form->cropper('poster','海报图片');
        $form->textarea('intro','描述');
        $form->datetime('start_at','开始时间');
        $form->datetime('end_at','结束时间');
        $form->slider('priority','优先度')->options(['max' => 10, 'min' => 1, 'step' => 1, 'postfix' => '']);

        $states = [
            'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '暂停', 'color' => 'danger'],
        ];
        
        $form->switch('state', '状态')->states($states)->default(1);

        // $form->display('load_num','加载次数');
        // $form->display('click_num','点击次数');
        $form->display('created_at','Created at');
        $form->display('updated_at','Updated at');

        return $form;
    }
}
