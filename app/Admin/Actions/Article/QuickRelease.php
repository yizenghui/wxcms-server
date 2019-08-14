<?php

namespace App\Admin\Actions\Article;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class QuickRelease extends Action
{
    protected $selector = '.quick-release';

    public function handle(Request $request)
    {
        // $request ...
        return $this->response()->info('您可以快速转载互联网上的文章')->redirect('/admin/article/quickcreate');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default quick-release">快速发布</a>
HTML;
    }
}