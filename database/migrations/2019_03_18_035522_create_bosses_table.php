<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBossesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bosses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenancy_id')->index()->comment('数据所属项目id');
            $table->string('name')->comment('商家名');
            $table->string('wxid')->comment('微信号ID');
            $table->string('qrcode')->comment('微信二维码')->nullable();
            $table->string('bio')->comment('商家简介')->nullable();
            $table->string('avatar')->comment('商家头像')->nullable();
            $table->string('mobile')->comment('商家电话')->nullable();
            $table->string('email')->comment('商家邮箱')->nullable();
            $table->string('slack')->comment('slack通知')->nullable();
            $table->string('linkman')->comment('联系人')->nullable();
            $table->text('remarks')->comment('备注')->nullable();
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
        Schema::dropIfExists('bosses');
    }
}
