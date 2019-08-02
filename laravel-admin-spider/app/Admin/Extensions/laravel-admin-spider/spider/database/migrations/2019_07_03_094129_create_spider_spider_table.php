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
            $table->string('name', 255)->default('');
            $table->unsignedInteger('tasknum')->default(1);
            $table->string('domains', 2000);
            $table->string('scan_urls', 2000);
            $table->string('list_url_regexes', 2000);
            $table->string('content_url_regexes', 2000);
            $table->string('fields', 2000);
            $table->string('export_config', 2000);
            $table->string('db_config', 2000);
            $table->string('queue_config', 2000);
            //相当于深度
            $table->unsignedInteger('max_depth')->default(0);
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
