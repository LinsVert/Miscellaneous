<?php
/**
 * Created by PhpStorm.
 * User: lins
 * Date: 2018/12/26
 * Time: 17:07
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Selector
 * @package App\Facades
 * @method static select($html, $selector, $selector_type = 'xpath')
 */
class Selector extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Selector';
    }
}