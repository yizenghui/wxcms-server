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
use App\Models\Carousel;
use Illuminate\Support\MessageBag;
use Admin;

class ArticleController extends Controller
{
    use HasResourceActions;

    // 创建初始数据
    public function createInitData(Content $content){
        $appid = Admin::user()->id;
        $topic = Topic::create(['appid'=>$appid,'name'=>'接入教程','order'=>0,'cover'=>'https://wx1.wechatrank.com/base64img/20190531160541.png','intro'=>'wxcms教程文章','state'=>1]);
        $author = Author::create(['appid'=>$appid,'name'=>'管理员','state'=>1,'user_id'=>0]);
        $carousel = Carousel::create([
            'appid'=>$appid,
            'name'=>'测试首页轮播图片1',
            'cover'=>'https://wx1.wechatrank.com/base64img/20190531155858.png',
            'position'=>1,
            'genre'=>1,
            'state'=>1,
            'start_at'=>date('Y-m-d 00:00:00'),
            'end_at'=>date('Y-m-d 00:00:00',time()+7*86400),
            'priority'=>1,
            ]);
        $carousel2 = Carousel::create([
            'appid'=>$appid,
            'name'=>'测试用户首页轮播图片2',
            'cover'=>'https://wx1.wechatrank.com/base64img/20190531160215.png',
            'position'=>2,
            'genre'=>1,
            'state'=>1,
            'start_at'=>date('Y-m-d 00:00:00'),
            'end_at'=>date('Y-m-d 00:00:00',time()+7*86400),
            'priority'=>1,
            ]);
        $article = Article::create([
            'appid'=>$appid,
            'title'=>'快速开始',
            'intro'=>'快速开始教程，您可以根据教程，快速创建您的小程序博客内容管理系统',
            'cover'=>'https://wx1.wechatrank.com/base64img/20190531162026.png',
            'topic_id'=>$topic->id,
            'author_id'=>$author->id,
            'state'=>1,
            'comment_status'=>1,
            'recommend_at'=>date('Y-m-d 00:00:00',time()+7*86400),
            'body'=>'## 快速开始

0. 注册小程序 获取 AppID(小程序ID)  AppSecret(小程序密钥)

1. 联系客服 获取 WeContr 帐号，登录WeContr后台 https://readfollow.com/admin 配置 AppID和AppSecret

2. 在WeContr后台 https://readfollow.com/admin  通过 "代发布小程序代码" 板块的 "小程序代码管理"  授权WeContr管理您的小程序 快速发布您的小程序代码

3. 在微信小程序后台添加您的小程序 类目 如 "文娱》资讯" 或其它类目

4. 在WeContr小程序代码管理提交小程序代码

5. 在 WeContr后台 https://readfollow.com/admin 添加初始数据

6. 扫描二维码进行体验小程序

7. 更改或删除测试数据信息，添加您的新数据

8. 在WeContr小程序代码管理处提交审核

9. 收到审核通过通知后，在WeContr小程序代码管理处发布您的小程序代码。（完成）

### 后台操作：
后台地址 https://readfollow.com/admin

1. 添加专题， 需要背景图、名称和描述
2. 添加作者， 只有名字也可以
3. 添加文章， 标题、描述、封面、正文等 正文使用markdown语法，请自行摸索。 注1
4. 添加轮播， 首页和后台都务必添加至少一个



注1：推荐文章11以上访问量需要才会在首页显示，可在专题页面找到




## 二次开发

安装（更新） wepy 命令行工具。
    npm install wepy-cli -g
    
获取小程序端代码
    git clone  https://github.com/yizenghui/wxcms.git

或者下载文件并解压 https://github.com/yizenghui/wxcms/archive/master.zip

进入根目录	`cd wxcms`

安装依赖 `npm install`

开发实时编译 `wepy build --watch`

修改小程序配置 `src/app.wpy`

`api_token: \'0RPJhqvW6gMp\',`


修改`0RPJhqvW6gMp`为由 https://readfollow.com/admin/auth/setting#tab-form-2 所获得的 api token


开发者工具导入项目

选择根目录中的 dist 为代码目录

注意：请关闭 ES6 转 ES5

## 技术支持(兼职客服)
email: 121258121@qq.com',
            ]
        );
        $article2 = Article::create([
            'appid'=>$appid,
            'title'=>'快速开发',
            'intro'=>'快速开发教程，您可以根据教程，快速开发您的小程序博客内容管理系统',
            'cover'=>'https://wx1.wechatrank.com/base64img/20190531162026.png',
            'topic_id'=>$topic->id,
            'author_id'=>$author->id,
            'state'=>1,
            'comment_status'=>1,
            'recommend_at'=>date('Y-m-d 00:00:00',time()+7*86400),
            'body'=>'## 快速接入

0. 获取并解压`dist.7ip`到你的本地

1. 注册小程序 获取 AppID(小程序ID)  AppSecret(小程序密钥)

2. 登录后台获取api token (假设是`wRZJFqYAvbRk` ) 链接： https://readfollow.com/admin/auth/setting#tab-form-2  并设置 AppID，AppSecret

3. 替换 dist/app.js 中的 api_token (用文本编辑器打开搜索\'api\_token\') `api_token:"0RPJhqvW6gMp", `中的`0RPJhqvW6gMp`替换为你的api token`wRZJFqYAvbRk`

4. 开发者工具导入项目,选择根目录中的 dist 为代码目录,设置appid为自己 AppID(小程序ID) 

5. 小程序后台添加

    *	request合法域名 https://readfollow.com

    *	downloadFile合法域名 https://wx1.wechatrank.com



### 后台操作：
后台地址 https://readfollow.com/admin

1. 添加专题， 需要背景图、名称和描述
2. 添加作者， 只有名字也可以
3. 添加文章， 标题、描述、封面、正文等 正文使用markdown语法，请自行摸索。 注1
4. 添加轮播， 首页和后台都务必添加至少一个



注1：推荐文章11以上访问量需要才会在首页显示，可在专题页面找到




## 二次开发

安装（更新） wepy 命令行工具。
    npm install wepy-cli -g
    
获取小程序端代码
    git clone  https://github.com/yizenghui/wxcms.git

或者下载文件并解压 https://github.com/yizenghui/wxcms/archive/master.zip

进入根目录	`cd wxcms`

安装依赖 `npm install`

开发实时编译 `wepy build --watch`

修改小程序配置 `src/app.wpy`

`api_token: \'0RPJhqvW6gMp\',`


修改`0RPJhqvW6gMp`为由 https://readfollow.com/admin/auth/setting#tab-form-2 所获得的 api token


开发者工具导入项目

选择根目录中的 dist 为代码目录

注意：请关闭 ES6 转 ES5

## 技术支持(兼职客服)
email: 121258121@qq.com',
            ]
        );
        return $content->withSuccess('提醒', '已经为您创建初始数据，您可以<a href="/admin">返回首页</a>进行其它操作');
    }

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

        
        $show->rewardlogs('激励文章', function ($readlog) {
            $readlog->id();
            $readlog->name();
            $readlog->disableCreateButton();
            $readlog->disableExport();
            $readlog->disableRowSelector();
            $readlog->disableActions();
        });
        $show->likelogs('点赞记录', function ($readlog) {
            $readlog->id();
            $readlog->name();
            $readlog->disableCreateButton();
            $readlog->disableExport();
            $readlog->disableRowSelector();
            $readlog->disableActions();
        });
        $show->readlogs('阅读记录', function ($readlog) {
            $readlog->id();
            $readlog->name();
            $readlog->disableCreateButton();
            $readlog->disableExport();
            $readlog->disableRowSelector();
            $readlog->disableActions();
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
            $form->simplemde('body','正文')->rules('required')->required()->help('上传图片到图床复制Markdown语法标签。 <br>>>>文章正文转换成Markdown语法格式<a target="_blank" href="http://39.108.89.44:1323/">Article2Markdown</a>');
            
                
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

            $comment_status_arr = [0=>'隐藏评论',1=>'自由评论',2=>'严格审核',4=>'关闭评论'];
            $form->select('comment_status','评论设置')->options($comment_status_arr)->default(0)->help('说明<br>隐藏评论：文章底部不会显示评论功能组件；<br>自由评论：任何用户可以发布评论并立即展示；<br>严格审核：评论内容需要通过管理员审核后才能展示；<br>关闭评论：显示通过审核的评论记录但不允许新增评论');
          

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
