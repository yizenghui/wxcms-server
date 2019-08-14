<?php

namespace App\Admin\Forms\FastImportArticle;

use Encore\Admin\Widgets\StepForm;
use Illuminate\Http\Request;

class Resource extends StepForm
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '快速导入文章';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $all = $request->all();
        $json_str = file_get_contents(config('ecms.tomarkdownapi').urlencode($request->get('url')));
        $data = json_decode($json_str,true);
        if(!$data){
            return $this->next(['title'=>'','body'=>'']);
        }
        return $this->next(['title'=>$data['title'],'body'=>$data['content']]);
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('url')->rules('required')->help('粘贴要导入的资源的网站地址');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return [
            'url'       => '',
        ];
    }
}
