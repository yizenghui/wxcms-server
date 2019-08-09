<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appid')->index()->comment('数据所属项目id');
            $table->integer('user_id')->comment('粉丝id');
            $table->string('name')->comment('用户昵称');
            $table->string('truename')->comment('真实姓名')->nullable();
            $table->string('avatar')->comment('头像')->nullable();
            $table->text('intro')->comment('介绍')->nullable();
            $table->string('mobile')->comment('手机')->nullable();
            $table->string('email')->comment('邮箱')->nullable();
            $table->string('wxid')->comment('微信号')->nullable();
            $table->string('wxappid')->comment('微信公众号')->nullable();
            $table->timestamp('sign_at')->comment('签约时间')->nullable();
            $table->integer('share_id')->comment('分享策略')->default(0);
            $table->string('reward_adid')->comment('激励式视频id')->nullable(); // 不同作者用不同广告ID， 分成可依据流量主后台统计
            $table->string('banner_adid')->comment('banner广告id')->nullable();
            $table->string('reward_qrcode')->comment('赞赏码')->nullable();
            $table->integer('point')->comment('剩余积分')->default(0);
            $table->integer('current_point')->comment('当前可用积分')->default(0); // 注：这个参数在发工资时由剩余积分同步过来 (用于限制用户这个月新获取的积分不能在结算前使用)
            $table->integer('total_point')->index()->comment('总积分')->default(0);
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
        Schema::dropIfExists('authors');
    }
}
