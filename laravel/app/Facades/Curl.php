<?php
/**
 * Created by PhpStorm.
 * User: lins
 * Date: 2018/12/26
 * Time: 14:40
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Curl
 * @package App\Facades
 * @see \App\Libraries\Request
 * @method static get($url, $fields = [], $allow_redirects = true)
 * @method static post($url, $fields = [], $files = [], $allow_redirects = true)
 * @method static set_timeout($timeout)
 * @method static set_header($key, $value)
 * @method static set_useragent($useragent)
 * @method static set_referer($referer)
 * @method static getResponeCode()
 * @method static get_error()
 * @method static set_error($error = '')
 * @method static get_cookies($domain = '')
 * @method static set_proxy($proxy = '')
 */
class Curl extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Curl';
    }
}