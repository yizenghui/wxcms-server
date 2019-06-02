<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) { //每日任务表 每日组队、签到、阅读、点赞、评论、吸新、粉丝签到
            $table->increments('id');
            $table->integer('tenancy_id')->index()->comment('数据所属项目id');
            $table->integer('user_id')->comment('关联用户id')->default(0);
            $table->integer('did')->comment('日期ID 格式：20190101')->default(0);
            $table->integer('team_id')->comment('成功组队id')->default(0);
            $table->timestamp('sign_at')->comment('签到时间')->nullable();
            $table->timestamp('reward_at')->comment('激励时间')->nullable();
            $table->integer('read_num')->comment('阅读量')->default(0);
            $table->integer('like_num')->comment('点赞数')->default(0);
            $table->integer('comment_num')->comment('评论数')->default(0);
            $table->integer('join_num')->comment('吸引新人')->default(0);
            $table->integer('sign_num')->comment('粉丝签到数')->default(0);
            $table->integer('total')->comment('今日获行总积分')->default(0);
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
        Schema::dropIfExists('tasks');
    }
}
