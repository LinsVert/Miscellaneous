<?php

namespace App\Utils;

/**
 * trait 单例模式
 */
trait Singleton
{
    //单例不能对外
    private static $instance;

    static function getInstance(...$args)
    {
        if (!isset(self::$instance)) {
            //后期静态绑定
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }

}