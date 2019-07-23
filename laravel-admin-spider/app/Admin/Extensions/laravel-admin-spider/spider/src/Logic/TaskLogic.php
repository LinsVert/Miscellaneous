<?php

namespace Linsvert\Spider\Logic;

use Cron\CronExpression;
use Linsvert\Spider\Utils\Spider;
use Linsvert\Spider\Http\Models\TaskModel;

class TaskLogic
{
    public static function start()
    {
        //基本逻辑
        //fork 出n个子进程用于计划调度
        $taskModel = TaskModel::where('loop_times', 1)->where('times', 0)->orWhere('loop_times', '>', 1)->get();
        //如果是爬虫类重复任务 如爬取个网页 的图片 那么 重复任务的调度问题 需要解决(比如任务已经在跑了) 是否 重复跑的问题
        if ($taskModel) {
            $exec = "ps -aux | grep 'Linsvert task process child process php %s'";
            foreach ($taskModel as $key => $value) {
                if ($value->withoutOverlapping == 1) {
                    exec(sprintf($exec, $value->id), $_pid);
                    //如果子进程还在运行 则不运行
                    if (count($_pid) > 1) {
                        continue;
                    }
                }
                $pid = pcntl_fork();
                if ($pid == -1) {
                    echo 'fork error' . PHP_EOL;
                    return false; 
                } else if ($pid == 0) {
                    cli_set_process_title('Linsvert task process child process php ' . $value->id);
                    self::runSpider($value);
                    //运行完退出子进程
                    exit();
                } else {
                    cli_set_process_title('Linsvert task process php ' . $value->id);
                }
            }


            while (pcntl_waitpid(0, $status) != -1) { 
                $status = pcntl_wexitstatus($status); 
                echo "Child $status completed\n"; 
            } 
        }
    }
     
    protected static function runSpider(TaskModel $taskModel)
    {
        echo 'Start spider task name :' . $taskModel->name . ' spider name :' . $taskModel->spider->spider_name . ' task id:' . $taskModel->id;
        //查看调度命令检测使用 https://github.com/dragonmantank/cron-expression
        $isDue = CronExpression::factory($taskModel->crontab)->isDue('now', $taskModel->timeZone);
        if ($isDue == false) {
            return false;
        }
        //如果可以运行 获取爬虫配置 调度
        Spider::run($taskModel);
    }
}