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
            $table->string('name')->comment('名称');
            $table->integer('num')->comment('数量');
            $table->integer('price')->comment('单价');
            $table->integer('total')->comment('总价');
            $table->string('cover')->comment('封面图')->nullable();
            $table->integer('goods_id')->comment('商品id');
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
