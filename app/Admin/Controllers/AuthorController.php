<?php

namespace App\Admin\Controllers;

use App\Models\Author;
use App\Models\Share;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;

class AuthorController extends Controller
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
            ->header('作者')
            ->description('管理您的作者')
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
            ->header('作者详细')
            ->description('查看作者详细信息')
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
        $data = Author::findOrFail($id);
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
        $grid = new Grid(new Author);
        $grid->model()->where('appid', '=', Admin::user()->id);
        $grid->id('ID');
        $grid->name('作者名');
        // $grid->avatar()->display(function ($url) {
        //     if(!$url) return '';
        //     $image = "<img style='width: 90px;' src='/uploads/{$url}'>";
        //     return $image;
        // });
        $grid->state('状态')->switch();
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        $grid->filter(function($filter){
            $filter->like('name', '作者名');
        });
        // $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
        //     $create->text('name', '名称')->default('xx');
        //     $create->text('appid')->default(Admin::user()->id); // 要有appid才行
        //     $create->select('state', '状态')->options([
        //         1 => '展示',
        //         0 => '屏蔽',
        //     ])->default(1);
        // });
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
        $show = new Show(Author::findOrFail($id));

        $show->id('ID');
        $show->name('作者');
        $show->avatar('头像')->image();
        $show->intro('介绍');
        $show->sign_at('签约时间');
        $show->user_id('绑定粉丝号ID');
        $show->state('状态')->as(function($v){
            $state_arr = [
                0=>'暂停',
                1=>'启用',
            ];
            if(isset($state_arr[$v])) return $state_arr[$v];
            return $v;
        });
        $show->articles('文章', function ($article) {
            $article->resource('/admin/article');
            $article->id();
            $article->title('标题');
            $article->rewarded('激励');
            $article->view('阅读');
            $article->commented('评论');
            $article->liked('点赞');
            $article->created_at();
            $article->updated_at();
            $article->filter(function ($filter) {
                $filter->like('title','标题');
            });
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
        $form = new Form(new Author);

        $form->display('ID');
        $app = Admin::user()->app;
        // 抛出错误信息
        $form->saving(function ($form) use($app) {

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
        
        $form->text('name','作者名');
        $form->number('user_id','粉丝ID')->default(0)->help('为作者绑定粉丝ID');
        $form->cropper('reward_qrcode','赞赏码')->help('您可以上传作者的赞赏码，用于小程序文章详情页赞赏功能。（获取：微信 》收付款 》赞赏码 》保存）');
        // $form->cropper('avatar','头像');
        $form->textarea('intro','描述');
        
        $share_arr = collect([0=>'无'])->union(Share::where('appid', '=', Admin::user()->id)->get()->pluck('name', 'id'))->all();
        $form->select('share_id','自定义分享')->options($share_arr)->default(0)->help('需预设分享策略');
        // $form->text('mobile','手机');
        // $form->text('email','邮箱');
        // $form->text('wxid','微信号');
        $form->text('wxappid','微信公众号');
        $form->datetime('sign_at','签约时间');
        // $form->number('point','剩余积分')->default(0);
        // $form->number('current_point','当前可用积分')->default(0);
        // $form->number('total_point','总积分')->default(0);
        
        if($app && $app->isvip){
            $form->text('reward_adid', '激励式视频广告ID')->help('不同作者用不同广告ID， 分成可依据流量主后台统计');
            $form->text('banner_adid', 'banner广告ID')->help('不同作者用不同广告ID， 分成可依据流量主后台统计');
        }
        $states = [
            'on'  => ['value' => 1, 'text' => '展示', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '屏蔽', 'color' => 'danger'],
        ];
        
        $form->switch('state', '状态')->states($states)->default(1);
        $form->display('created_at', 'Created at');
        $form->display('updated_at', 'Updated at');

        return $form;
    }
}
