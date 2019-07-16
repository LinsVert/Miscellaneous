<?php

/**
 * 基本调度中心
 * 需要配合定时任务使用
 * 主要异步调度各种任务 不做具体逻辑
 */

 namespace LinsVert\Spider\Console;

 use Illuminate\Console\Command;
 use Linsvert\Spider\Logic\TaskLogic;
 use Linsvert\Spider\Utils\Spider;
 use Illuminate\Console\Scheduling\Schedule;

 class TaskCommand extends Command
 {
     protected $signature = 'linsvert:spider';

     protected $description = 'laravel-admin-spider 调度中心';

     public function __construct()
     {
         parent::__construct();
     }

     public function handle(Schedule $schedule) {
        //因php使用非阻塞麻烦 所以只能用子进程来调用
        // 根据鸟哥的说法
        //目前如何实现调度问题:
        //1.在系统自定义命令执行之后 改command只会执行一次,所以如何在这里面实现一个自定义的调度问题
        //todu
        echo 'start' . PHP_EOL;
        // TaskLogic::start();
        Spider::test();
        
     }
 }