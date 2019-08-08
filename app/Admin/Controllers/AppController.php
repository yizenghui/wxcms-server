<?php

namespace App\Admin\Controllers;

use App\Models\App;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use Admin;

class AppController extends Controller
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
        if( !Admin::user()->isAdministrator() ){
            return $content
            ->withError('出错了', '您未获得访问权限');
        }
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
        if( !Admin::user()->isAdministrator() ){
            return $content
            ->withError('出错了', '您未获得访问权限');
        }
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
        
        if( !Admin::user()->isAdministrator() ){
            return $content
            ->withError('出错了', '您未获得访问权限');
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
        if( !Admin::user()->isAdministrator() ){
            return $content
            ->withError('出错了', '您未获得访问权限');
        }
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
        $grid = new Grid(new App);

        $grid->id('ID');
        $grid->app_name('应用名');
        $grid->appid('管理员ID');
        $grid->app_id('APP_ID');
        $grid->vip_deadline('会员截止时间');
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
        $show = new Show(App::findOrFail($id));

        $show->id('ID');

        $show->vip_deadline('VIP截止时间');
        
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
        $form = new Form(new App);

        $form->tab('应用设置', function (Form $form) {
            
        
            $form->text('appid');

        

            $vip_status_arr = [0=>'普通用户',1=>'体验VIP用户',2=>'免费VIP用户',30=>'月度VIP',90=>'季度VIP',365=>'包年VIP',999=>'合作伙伴'];
            $form->select('vip_status','会员类型')->options($vip_status_arr)->default('0')->help('会员类型和截止时间需要同时为有效值');
            $form->datetime('vip_deadline','VIP截止时间')->default(date('Y-m-d 23:59:59',time()+86400*7));
        

            $form->number('quota', '当前月额度')->help('如需提升额度请联系客服');
            $form->number('current_quota', '本月已用额度')->help('每日更新统计');
            $form->display('total_point', '总使用额度')->help('每日更新统计');

            $form->text('app_name', '应用名称')->rules('required');
            $form->text('app_id', 'AppID');
            $form->text('app_secret', 'AppSecret');
            $form->text('reward_adid', '激励式视频广告ID')->help('签到激励视频ID，由浏量主后台获得');
            $form->text('banner_adid', 'banner广告ID')->help('banner广告ID，由浏量主后台获得');
            
            $topic_arr = [
                'red'=>'Red', 'orange'=>'Orange', 'green'=>'Green', 'purple'=>'Purple', 'pink'=>'Pink', 'blue'=>'Blue',
            ];
            $form->select('template_topic','小程序主题风格')->options($topic_arr)->default('green')->help('需预设上传风格图标，设置后删除最近使用小程序重新打开生效。');
            $form->text('default_search', '搜索默认值'); //->help('关键词一 关键词二(通过空格搜索包含关键词一或者包含关键词二的内容)')
            
            $states = [
                'on'  => ['value' => 1, 'text' => '是', 'color' => 'danger'],
                'off' => ['value' => 0, 'text' => '否', 'color' => 'success'],
            ];
            $form->switch('secret_locking', '锁定密令')->states($states)->default(0)->help('锁定后用户不能通过用户设置更改其密令');

            $states = [
                'on'  => ['value' => 1, 'text' => '展示', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '隐藏', 'color' => 'danger'],
            ];
            $form->switch('reward_status', '激励记录 vip')->states($states)->default(0);
            $form->switch('rank_status', '排行榜')->states($states)->default(0);
            $form->switch('shopping_status', '积分商城')->states($states)->default(0);
            $form->switch('point_logs_status', '积分记录')->states($states)->default(0);
            $form->switch('follow_status', '展示关注组件')->states($states)->default(0)->help('在小程序内展示对同一主体公众号有效的关注组件 <a target="_blank" href="https://developers.weixin.qq.com/miniprogram/dev/component/official-account.html">文档</a>');
           
        })->tab('运营管理', function (Form $form) {
            $form->number('point_default_fromid', '默认奖励id')->default(0)->help('如果是自然来路用户，可以设置其作为奖励对象为当前运营者粉丝id。');
            $states = [
                'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '暂停', 'color' => 'danger'],
            ];
            $form->switch('point_day_sign_num', '用户签到')->states($states)->default(1);
            $form->number('point_sign_action', '签到积分')->default(0);
            $form->switch('point_day_reward_num', '签到激励')->states($states)->default(0);
            $form->number('point_reward_action', '签到激励积分')->default(0);
            $form->number('point_author_article_reward_action', '作者文章被激励积分')->default(0)->help('需要给作者绑定激励式视频广告id');
     
            $form->divide();
            $form->switch('point_repeated_incentives', '重复激励文章奖励')->states($states)->default(0);
            $form->number('point_reward_article_action', '激励文章积分')->default(0);
            $form->number('point_day_reward_article_num', '激励文章次数')->default(0);

            // $form->divide();
            // $form->switch('point_day_team_double_enabled', '组队双倍积分')->states($states)->help('粉丝组成5人队伍后，可获得双倍任务(分隔线下)奖励');
            // $form->divide();
            $form->switch('point_rereading_reward', '重复阅读奖励')->states($states)->default(0);
            $form->number('point_read_action', '阅读积分')->default(0);
            $form->number('point_day_read_num', '阅读次数')->default(0);
            $form->number('point_like_action', '点赞积分')->default(0);
            $form->number('point_day_like_num', '点赞次数')->default(0);
            $form->switch('point_show_task', '显示任务')->states($states)->default(0);
            
            $form->display('help', '备注')->default('上述活动和任务，粉丝每天都可以做。');
        })->tab('渠道版块', function (Form $form) {
            $states = [
                'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '暂停', 'color' => 'danger'],
            ];
            $form->switch('point_channel_status', '渠道奖励')->states($states)->default(1);
            $form->number('point_interview_action', '邀请积分')->default(0);
            $form->number('point_day_interview_num', '邀请人数')->default(0);
            $form->number('point_day_fansign_action', '受邀人签到')->default(0);
            $form->number('point_day_fansign_num', '受邀人签到奖励次数')->default(0);
            $form->number('point_fanreward_action', '受邀人激励')->default(0);
            $form->number('point_day_fanreward_num', '受邀人激励奖励次数')->default(0);
            $form->number('point_share_action', '渠道访问')->default(0);
            $form->number('point_day_share_num', '渠道访问人数')->default(0);
            $form->display('help', '备注')->default('开启渠道功能后，粉丝分享小程序作为渠道源。');
        });

        
        $form->tools(function (Form\Tools $tools) {

            // Disable `List` btn.
            $tools->disableList();
        
            // Disable `Delete` btn.
            $tools->disableDelete();
        
            // Disable `Veiw` btn.
            $tools->disableView();
        
            // Add a button, the argument can be a string, or an instance of the object that implements the Renderable or Htmlable interface
            // $tools->add('<a class="btn btn-sm btn-danger"><i class="fa fa-user"></i>&nbsp;&nbsp;联系客服</a>');
        });
        

        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
