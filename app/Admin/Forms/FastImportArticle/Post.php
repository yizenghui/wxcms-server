<?php

namespace App\Admin\Forms\FastImportArticle;

use Encore\Admin\Widgets\StepForm;
use Illuminate\Http\Request;
use Admin;

class Post extends StepForm
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '快速生成文章';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        dd($request->all());

        admin_success('Processed successfully.');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->hidden('appid')->default(Admin::user()->id);

        $this->text('title','标题')->rules('required')->required();
        $this->simplemde('body','正文')->rules('required')->required()->help('上传图片到图床复制Markdown语法标签。 <br>>>>文章正文转换成Markdown语法格式<a target="_blank" href="http://39.108.89.44:1323/">Article2Markdown</a>');
        
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $all = $this->all();
        $title = $all['resource']['title'];
        $body = $all['resource']['body'];
        return [
            'title'       => $title,
            'body'      => $body,
            'appid' => Admin::user()->id,
        ];
    }
}
