<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Encore\Admin\Form;
use App\Models\Share;
use Admin;
use Vinkla\Hashids\Facades\Hashids;
class AuthController extends BaseAuthController
{

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    public function settingForm()
    {
        $class = config('admin.database.users_model');
// dd($class);
        $form = new Form(new $class());

        
        $form->tab('用户信息', function (Form $form) {
   
            $form->display('username', trans('admin.username'));
            $form->image('avatar', trans('admin.avatar'))->uniqueName();
            $form->password('password', trans('admin.password'))->rules('confirmed|required');
            $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

                
            $form->setAction(admin_base_path('auth/setting'));

            $form->ignore(['password_confirmation']);

        })->tab('应用设置', function (Form $form) {
            
            $form->display('api_token', 'api_token')->default(Hashids::encode( Admin::user()->id,20190504));

            $form->display('app.quota', '当前月额度')->help('如需提升额度请联系客服');
            $form->display('app.current_quota', '本月已用额度')->help('每日更新统计');
            $form->display('app.total_point', '总使用额度')->help('每日更新统计');

            $form->text('app.app_name', '应用名称')->rules('required');
            $form->text('app.app_id', 'AppID')->rules('required');
            $form->text('app.app_secret', 'AppSecret')->rules('required');
            $form->text('app.reward_adid', '激励式视频广告ID')->help('签到激励视频ID，由浏量主后台获得');
            $form->text('app.banner_adid', 'banner广告ID')->help('banner广告ID，由浏量主后台获得');
            
            $states = [
                'on'  => ['value' => 1, 'text' => '展示', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '隐藏', 'color' => 'danger'],
            ];
            $share_arr = collect([0=>'无'])->union(Share::where('appid', '=', Admin::user()->id)->get()->pluck('name', 'id'))->all();
            $form->select('app.index_share_id','首页自定义分享')->options($share_arr)->default(0)->help('需预设分享策略');
            $form->select('app.topic_share_id','专题首页自定义分享')->options($share_arr)->default(0)->help('需预设分享策略');
            $form->text('app.default_search', '搜索默认值'); //->help('关键词一 关键词二(通过空格搜索包含关键词一或者包含关键词二的内容)')
            
            $form->switch('app.reward_status', '激励记录')->states($states)->default(0);
            $form->switch('app.rank_status', '排行榜')->states($states)->default(0);
            $form->switch('app.shopping_status', '积分商城')->states($states)->default(0);
            $form->switch('app.point_logs_status', '积分记录')->states($states)->default(0);
        })->tab('运营管理', function (Form $form) {
            $form->number('app.point_default_fromid', '默认奖励id')->default(0)->help('如果是自然来路用户，可以设置其作为奖励对象为当前运营者粉丝id。');
            $states = [
                'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '暂停', 'color' => 'danger'],
            ];
            $form->switch('app.point_day_sign_num', '用户签到')->states($states)->default(1);
            $form->number('app.point_sign_action', '签到积分')->default(0);
            $form->switch('app.point_day_reward_num', '签到激励')->states($states)->default(0);
            $form->number('app.point_reward_action', '签到激励积分')->default(0);
            $form->number('app.point_author_article_reward_action', '作者文章被激励积分')->default(0)->help('需要给作者绑定激励式视频广告id');
     
            $form->divide();
            $form->switch('app.point_repeated_incentives', '重复激励文章奖励')->states($states)->default(0);
            $form->number('app.point_reward_article_action', '激励文章积分')->default(0);
            $form->number('app.point_day_reward_article_num', '激励文章次数')->default(0);

            // $form->divide();
            // $form->switch('point_day_team_double_enabled', '组队双倍积分')->states($states)->help('粉丝组成5人队伍后，可获得双倍任务(分隔线下)奖励');
            // $form->divide();
            $form->switch('app.point_rereading_reward', '重复阅读奖励')->states($states)->default(0);
            $form->number('app.point_read_action', '阅读积分')->default(0);
            $form->number('app.point_day_read_num', '阅读次数')->default(0);
            $form->number('app.point_like_action', '点赞积分')->default(0);
            $form->number('app.point_day_like_num', '点赞次数')->default(0);
            $form->switch('app.point_show_task', '显示任务')->states($states)->default(0);
            
            $form->display('help', '备注')->default('上述活动和任务，粉丝每天都可以做。');
        })->tab('渠道版块', function (Form $form) {
            $states = [
                'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '暂停', 'color' => 'danger'],
            ];
            $form->switch('app.point_channel_status', '渠道奖励')->states($states)->default(1);
            $form->number('app.point_interview_action', '邀请积分')->default(0);
            $form->number('app.point_day_interview_num', '邀请人数')->default(0);
            $form->number('app.point_day_fansign_action', '受邀人签到')->default(0);
            $form->number('app.point_day_fansign_num', '受邀人签到奖励次数')->default(0);
            $form->number('app.point_fanreward_action', '受邀人激励')->default(0);
            $form->number('app.point_day_fanreward_num', '受邀人激励奖励次数')->default(0);
            $form->number('app.point_share_action', '渠道访问')->default(0);
            $form->number('app.point_day_share_num', '渠道访问人数')->default(0);
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
        
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));

            return redirect(admin_base_path('auth/setting'));
        });

        return $form;
    }

}
