<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenancy_id')->index()->comment('数据所属项目id');
            $table->string('name');
            $table->string('cover')->nullable();
            $table->string('intro')->nullable();
            $table->integer('share_id')->comment('分享策略')->default(0);
            $table->integer('order')->default(0);
            /** 推广运营设置 */
            $table->integer('default_fromid')->default(0);
            $table->integer('sign_action')->default(0);
            $table->integer('day_sign_num')->default(0);
            $table->integer('read_action')->default(0);
            $table->integer('day_read_num')->default(0);
            $table->integer('like_action')->default(0);
            $table->integer('day_like_num')->default(0);
            $table->integer('interview_action')->default(0);
            $table->integer('day_interview_num')->default(0);
            $table->integer('fansign_action')->default(0);
            $table->integer('day_fansign_num')->default(0);
            $table->integer('team_double_enabled')->default(0);
            $table->boolean('state')->default(1);
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
        Schema::dropIfExists('topics');
    }
}
