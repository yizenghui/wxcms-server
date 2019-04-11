<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('用户昵称')->nullable();
            $table->string('openid')->unique()->comment('开放id');
            $table->integer('gender')->comment('性别')->default(0);
            $table->string('city')->comment('用户所在城市')->nullable();
            $table->string('avatar')->comment('用户头像')->nullable();
            $table->string('bio')->comment('用户简介')->nullable();
            $table->dateTime('lock_at')->comment('锁定用户(不允许操作)')->nullable();
            $table->string('wxid')->comment('微信号(加好像后客服填写)')->nullable();
            $table->text('remarks')->comment('管理员备注')->nullable();
            $table->integer('fromid')->comment('推荐人')->default(0);
            $table->integer('point')->comment('剩余积分')->default(0);
            $table->integer('current_point')->comment('当前可用积分')->default(0); // 注：这个参数在发工资时由剩余积分同步过来 (用于限制用户这个月新获取的积分不能在结算前使用)
            $table->integer('total_point')->index()->comment('总积分')->default(0);
            $table->string('session_key')->comment('session_key')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fans');
    }
}
