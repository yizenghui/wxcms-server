<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appid')->index()->comment('数据所属项目id');
            $table->string('title')->comment('标题');
            $table->integer('author_id')->comment('作者')->default(0);
            $table->string('intro')->comment('描述(导读)')->nullable();
            $table->string('cover')->comment('封面')->nullable();
            $table->string('audio')->comment('音频')->nullable();
            $table->string('video')->comment('视频')->nullable();
            $table->integer('share_id')->comment('分享策略')->default(0);
            $table->text('body')->comment('正文');
            $table->integer('topic_id')->comment('专题id')->default(0);
            $table->integer('view')->comment('页面展示数')->default(0);
            $table->integer('commented')->comment('评论数')->default(0);
            $table->integer('liked')->comment('喜欢人数')->default(0);
            $table->integer('rewarded')->comment('激励人次')->default(0);
            $table->boolean('state')->default(1);
            $table->dateTime('commented_at')->comment('最后评论时间戳')->nullable();
            $table->dateTime('recommend_at')->comment('推荐结束时间戳')->nullable();
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
        Schema::dropIfExists('articles');
    }
}
