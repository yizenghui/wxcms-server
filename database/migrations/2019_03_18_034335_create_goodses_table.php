<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goodses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('商品名称');
            $table->integer('cash_value')->comment('现金价值');
            $table->integer('point')->comment('兑换需要积分');
            $table->dateTime('lower_at')->comment('下架时间 如果小于当前 为已下架商品');
            $table->integer('stock')->default(0)->comment('库存量为0不再允许兑换');
            $table->integer('out')->default(0)->comment('出货量');
            $table->dateTime('invalid_at')->comment('兑换后生成有效期')->nullable();
            $table->string('cover')->comment('封面图')->nullable();
            $table->integer('boss_id')->comment('商品提供商家id');
            $table->string('intro')->comment('描述')->nullable();
            $table->text('body')->comment('详情页')->nullable();
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
        Schema::dropIfExists('goodses');
    }
}
