<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('关联用户id')->default(0);
            $table->integer('cash')->comment('正加负减 现金(单位:分)');
            $table->integer('total')->comment('小计总数 当前剩余总金额');
            $table->dateTime('settlement')->comment('结算时间')->nullable();
            $table->string('intro')->comment('描述')->nullable();
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
        Schema::dropIfExists('funds');
    }
}
