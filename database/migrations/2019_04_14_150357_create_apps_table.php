<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appid')->default(0); //绑定管理员id
            $table->integer('genre')->comment('应用类型')->default(0);
            $table->string('app_name')->comment('应用名')->nullable();
            $table->string('app_id')->comment('AppID')->nullable();
            $table->string('app_secret')->comment('AppSecret')->nullable();
            $table->string('reward_adid')->comment('激励式视频id')->nullable();
            $table->string('banner_adid')->comment('banner广告id')->nullable();
            $table->string('default_search')->comment('默认搜索值')->nullable();
            $table->integer('index_share_id')->comment('首页分享策略')->default(0);
            $table->integer('topic_share_id')->comment('专题首页分享策略')->default(0);
            $table->integer('quota')->comment('周期额度量')->default(0);
            $table->integer('current_quota')->comment('当前周期使用额度')->default(0); // 注：这个参数在发工资时由剩余积分同步过来 (用于限制用户这个月新获取的积分不能在结算前使用)
            $table->integer('total_quota')->comment('总使用额度')->default(0);
            $table->integer('attach_quota')->comment('附加额度数')->default(0);
            $table->boolean('point_enabled')->comment('积分系统是否启动?')->default(1);
            $table->boolean('point_day_sign_num')->comment('开启签到得积分功能')->default(1);
            $table->boolean('point_day_reward_num')->comment('开启激励得积分功能')->default(1);
            $table->integer('point_default_fromid')->comment('默认奖励pid')->default(0);
            $table->integer('point_sign_action')->comment('签到可以获得积分')->default(10);
            $table->integer('point_reward_action')->comment('激励可以获得积分')->default(10);
            $table->integer('point_read_action')->comment('阅读可以获得积分')->default(1);
            $table->integer('point_day_read_num')->comment('一天可以获得10次阅读积分')->default(10);
            $table->integer('point_like_action')->comment('点赞可以获得积分')->default(2);
            $table->integer('point_day_like_num')->comment('一天可以获得5次点赞积分')->default(5);
            $table->integer('point_interview_action')->comment('邀请新人访问可以获得积分')->default(10);
            $table->integer('point_day_interview_num')->comment('邀请新人访问可以获得积分')->default(100);
            $table->integer('point_day_fansign_action')->comment('受邀用户签到')->default(2);
            $table->integer('point_day_fansign_num')->comment('一天可以获得300次邀请新人签到积分')->default(300);
            $table->boolean('point_day_team_double_enabled')->comment('组队双倍积分功能是否开启')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
