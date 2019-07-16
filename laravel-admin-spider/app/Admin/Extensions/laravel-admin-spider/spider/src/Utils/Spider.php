<?php

namespace Linsvert\Spider\Utils;

use Linsvert\Spider\Utils\SpiderUtils\PhpSpider;

class Spider
{
     /**
     * run spider by task rule
     * @param  \Linsvert\Spider\Http\Models\TaskModel $taskModel
     */
    public static function run(TaskModel $taskModel)
    {
        $spider = $taskModel->spider;
        if (!$spider) {
            return false;
        }
        
        //增加运行次数
        $taskModel->increment('times');
    }
    /**
     *
     * used to run other spider
     * @param array $config
     * @return void
     */
    public static function runOther($config = [])
    {
        //todo
        return '';
    }

    public static function phpSpider($spider)
    {
        $config = [];
        $spider = new PhpSpider($config);
        dd($spider);
        // $spider->start();
    }
    public static function test() {
        self::phpSpider('');
    }
}