<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('appid')->index();
            $table->tinyInteger('position')->default(0);
            $table->tinyInteger('genre')->default(0);
            $table->boolean('state')->default(1);
            $table->string('cover')->nullable();
            $table->string('text')->nullable();
            $table->string('url')->nullable();
            $table->string('poster')->nullable();
            $table->string('gotoapp')->nullable();
            $table->string('intro')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->integer('priority')->default(0);
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
        Schema::dropIfExists('ads');
    }
}
