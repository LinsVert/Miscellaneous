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
        //一些启动参数请参阅 文档 https://doc.phpspider.org/callback.html
        $config = [];
        //如果设置了分布式 需要定义当前 的servicer id 问题怎么获取本地ip 如果都设置为一个id 会有异常吗
        $spider = new PhpSpider($config);
        //一些回调规则需要定义一下 
        //需要根据这个框架来添加一些自定义的东西
        //目前看来需要有:
        //1.启动规则
        //2.扫描页规则
        //3.保存配置
        //4.多进程任务 redis 配置 以及 mysql 配置
        //5.各种阶段的回调

        //需要根据这些定制化前端页面
        $spider->on_start = function ($phpspider) use ($spider) {
            //启动规则
        };

        $spider->on_extract_field = function($fieldname, $data, $page) {
            //
        };

        $spider->on_download_page = function () {
            //todo
        };

        $spider->on_scan_page = function () {

        };

        $spider->on_content_page = function () {

        };

        $spider->on_download_attached_page = function (){

        };

        $spider->on_handle_img = function () {
            
        };

    }
    public static function test() {
        self::phpSpider('');
    }
}