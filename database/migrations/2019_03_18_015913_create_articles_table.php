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
            $table->string('title')->comment('标题');
            $table->string('author')->comment('作者')->nullable();
            $table->string('intro')->comment('描述(导读)')->nullable();
            $table->string('cover')->comment('封面')->nullable();
            $table->string('audio')->comment('音频')->nullable();
            $table->string('video')->comment('视频')->nullable();
            $table->text('body')->comment('正文');
            $table->integer('topic_id')->comment('专题id');
            $table->integer('view')->comment('页面展示数');
            $table->integer('commented')->comment('评论数');
            $table->integer('liked')->comment('喜欢人数');
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
