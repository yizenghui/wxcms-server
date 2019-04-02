<?php

namespace App\Admin\Controllers;

use App\Models\Author;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
        $grid = new Grid(new Author);

        $grid->id('ID');
        $grid->name('姓名');
        // $grid->avatar()->display(function ($url) {
        //     if(!$url) return '';
        //     $image = "<img style='width: 90px;' src='/uploads/{$url}'>";
        //     return $image;
        // });
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
        $show = new Show(Author::findOrFail($id));

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
        $form = new Form(new Author);

        $form->display('ID');

        
        $form->text('name','作者名');
        $form->number('user_id','粉丝ID')->default(0);
        $form->cropper('avatar','头像');
        $form->textarea('intro','描述');
        $form->text('mobile','手机');
        $form->text('email','邮箱');
        $form->text('wxid','微信号');
        $form->text('wxappid','微信公众号');
        $form->datetime('sign_at','签约时间');
        $form->number('point','剩余积分')->default(0);
        $form->number('current_point','当前可用积分')->default(0);
        $form->number('total_point','总积分')->default(0);
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
