<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpiderSpiderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spider_spider', function (Blueprint $table) {
            $table->increments('id');
            //目标url
            $table->string('spider_name', 255)->default('');
            $table->string('url', 255)->default('');
            //列表页规则
            $table->string('list_type', 20)->default('');
            $table->string('list_rule', 255)->default('');
            //详情页规则
            $table->string('detail_type', 20)->default('');
            $table->string('detail_rule', 255)->default('');
            $table->unsignedTinyInteger('proxy')->default(0);
            //相当于深度
            $table->unsignedInteger('deep')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spider_spider');
    }
}
