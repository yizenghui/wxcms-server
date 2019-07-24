<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\Topic;
use App\Models\Author;
use App\Models\Share;
use Illuminate\Support\MessageBag;
use Admin;

class ArticleController extends Controller
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
        $data = Article::findOrFail($id);
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
        $grid = new Grid(new Article);
        $grid->model()->with('author');
        $grid->model()->where('appid', '=', Admin::user()->id);
        $grid->id('ID')->sortable();
        
        // dd(Topic::where('appid', '=', Admin::user()->id)->orderBy('id', 'desc')->get()->pluck('name', 'id'));
        $topic_arr = collect([0=>'无'])->union(Topic::where('appid', '=', Admin::user()->id)->orderBy('id', 'desc')->get()->pluck('name', 'id'))->all();
        $grid->topic_id('专题')->select($topic_arr);
        // $grid->position('ID')->display(function($id) { //editable
        //     return $topic_arr[$k];
        // });

        $grid->title('标题');
        $grid->author()->name('作者');
        $grid->view('浏览量')->sortable();
        $grid->liked('喜欢人数')->sortable();
        // $grid->commented('评论数');
        $grid->state('状态')->switch();
        $grid->recommend_at('推荐截止');
        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

        $grid->filter(function($filter){
            $filter->like('title', '标题');
            $author_arr = collect([0=>'无'])->merge(Author::all()->pluck('name', 'id'))->all();
            $filter->equal('author_id','作者')->select($author_arr);
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

        
        $show = new Show(Article::where('appid', '=', Admin::user()->id)->findOrFail($id));

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
        $form = new Form(new Article);

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

        $form->tab('内容', function (Form $form) {
            $form->text('title','标题')->rules('required')->required();
            $form->simplemde('body','正文')->rules('required')->required()->help('上传图片到图床<a target="_blank" href="https://sm.ms/">sm.ms</a>复制Markdown语法标签');
            
                
            $states = [
                'on'  => ['value' => 0, 'text' => '关闭', 'color' => 'success'],
                'off' => ['value' => 1, 'text' => '开启', 'color' => 'danger'],
            ];
            
            
            $form->switch('need_reward', '限制阅读')->states($states)->default(0)->help('用户需激励后方可阅读全文(需要设置作者激励id)');

            $topic_arr = collect([0=>'无'])->union(Topic::where('appid', '=', Admin::user()->id)->get()->pluck('name', 'id'))->all();
            $form->select('topic_id','所属专题')->options($topic_arr)->default(0);
            $author_arr = collect([0=>'无'])->union(Author::where('appid', '=', Admin::user()->id)->get()->pluck('name', 'id'))->all();
            $form->select('author_id','作者')->options($author_arr)->default(0);
            
            $states = [
                'on'  => ['value' => 1, 'text' => '上架', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '下架', 'color' => 'danger'],
            ];
            
            $form->switch('state', '状态')->states($states)->default(1);

        })->tab('更多', function (Form $form) {
            $form->cropper('cover','封面图')->cRatio(500,400);
            $form->textarea('intro','描述(导读)');
            $form->datetime('recommend_at','推荐截止')->default(date('Y-m-d 23:59:59',time()+86400*60));
            
            $share_arr = collect([0=>'无'])->union(Share::where('appid', '=', Admin::user()->id)->get()->pluck('name', 'id'))->all();
            $form->select('share_id','自定义分享')->options($share_arr)->default(0)->help('需预设分享策略');
            $form->text('audio','音频地址')->help('请确认链接地址可用');
            $form->text('video','腾讯视频vid')->help('目前仅支持vid参数视频');
        })/*->tab('数据', function (Form $form) {
            
            $form->display('view','浏览量')->default(0);
            $form->display('commented','评论数')->default(0);
            $form->display('liked','喜欢人数')->default(0);
            
        })*/;
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
