<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarouselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carousels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appid')->index();
            $table->string('name');
            $table->string('cover');
            $table->tinyInteger('position');
            $table->tinyInteger('genre');
            $table->boolean('state')->default(0);
            $table->string('url')->nullable();
            $table->string('poster')->nullable();
            $table->string('gotoapp')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->tinyInteger('priority')->default(0);
            $table->integer('load_num')->default(0);
            $table->integer('click_num')->default(0);
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
        Schema::dropIfExists('carousels');
    }
}
