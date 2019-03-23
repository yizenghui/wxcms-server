<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('goods_id')->comment('商品id');
            $table->string('name')->comment('名称');
            $table->integer('num')->comment('数量');
            $table->integer('point')->comment('单品消耗积分');
            $table->integer('point_total')->comment('总消耗积分');
            $table->integer('cash_total')->comment('价值现金总额');
            $table->string('cover')->comment('封面图')->nullable();
            $table->dateTime('delivery_at')->comment('发货时间')->nullable();
            $table->dateTime('lower_at')->comment('失效时间')->nullable();
            $table->text('prove')->comment('发货证明(如果与用户有纠纷)')->nullable();
            $table->dateTime('dispute_at')->comment('用户认定为纠纷订单')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
