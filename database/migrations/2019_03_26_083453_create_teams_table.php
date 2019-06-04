<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) { //
            $table->increments('id');
            $table->integer('appid')->index()->comment('数据所属项目id');
            $table->integer('did')->comment('日期ID 格式：20190101');
            $table->string('name')->comment('队伍名字')->nullable();
            $table->integer('user_id')->comment('队长id');
            $table->integer('total')->comment('今日获得总积分')->default(0);
            $table->timestamp('full_at')->comment('组队满员时间')->nullable();
            $table->string('user_ids')->comment('满员时记录队员ids')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('teamables', function (Blueprint $table) {// 使用定时任务删除
            $table->integer('team_id');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
        Schema::dropIfExists('teamables');
    }
    
    /**
     * 队伍中的成员
     */
    public function users()
    {
        return $this->belongsToMany(Fan::class,'teamables','team_id','user_id');
    }
}
