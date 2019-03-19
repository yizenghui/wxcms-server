<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash', function (Blueprint $table) {
            // 现金提现记录表
            $table->increments('id');
            $table->integer('user_id')->comment('粉丝id');
            $table->integer('point')->comment('使用积分sum');
            $table->integer('sum')->comment('提现金额(单位:分)');
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
        Schema::dropIfExists('cash');
    }
}
