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
        $grid->id('ID');
        $grid->title('标题');
        $grid->author('作者');
        $grid->view('浏览量');
        $grid->commented('评论数');
        $grid->liked('喜欢人数');
        $grid->recommend_at('推荐截止');
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
        $show = new Show(Article::findOrFail($id));

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
        $form->select('topic_id','所属专题')->options(Topic::all()->pluck('name', 'id'))->rules('required')->required();
        $form->text('title','标题')->rules('required')->required();
        $form->text('author','作者');
        $form->simplemde('body','正文')->rules('required')->required();
        $form->cropper('cover','封面图');
        $form->textarea('intro','描述(导读)');
        $form->number('view','浏览量')->default(0);
        $form->number('commented','评论数')->default(0);
        $form->number('liked','喜欢人数')->default(0);
        $form->datetime('recommend_at','推荐截止')->default(date('Y-m-d 23:59:59',time()+86400*60));
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
