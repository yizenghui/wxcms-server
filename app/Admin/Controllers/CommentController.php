<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Admin;

class CommentController extends Controller
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
            ->header('评论')
            ->description('管理您的评论')
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
        $grid = new Grid(new Comment);
        $grid->model()->where('appid', '=', Admin::user()->id);
        $grid->model()->with('commented','commentable');
        $grid->id('ID')->sortable();
        $grid->commented()->name('用户');
        
        $approve_arr = [0=>'待审核',1=>'审核通过',-1=>'审核不通过'];
        $grid->approve('状态')->select($approve_arr);
        $grid->comment('评论');
        $grid->commentable()->title('文章');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        $grid->disableCreateButton();
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
        $show = new Show(Comment::where('appid', '=', Admin::user()->id)->findOrFail($id));

        $show->id('ID');

        $show->commented('用户', function ($fans) {
            $fans->setResource('/admin/fans');
            $fans->id();
            $fans->name();
        });

        $show->commentable('文章信息', function ($article) {
            $article->setResource('/admin/article');
            $article->id();
            $article->title();
        });

        $show->approve('状态')->as(function($v){
            $approve_arr = [0=>'待审核',1=>'审核通过',-1=>'审核不通过'];
            if(isset($approve_arr[$v])) return $approve_arr[$v];
            return $v;
        });
        $show->comment('评论');

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
        $form = new Form(new Comment);

        $form->display('id','ID');
        
        $form->display('commentable.title','标题');
        $form->display('commented.name','用户');
        
        $approve_arr = [0=>'待审核',1=>'审核通过',-1=>'审核不通过'];
        $form->select('approve','状态')->options($approve_arr)->default(0);
        $form->textarea('comment', '评论');
        $form->display('created_at', 'Created at');
        $form->display('updated_at', 'Updated at');

        return $form;
    }
}
