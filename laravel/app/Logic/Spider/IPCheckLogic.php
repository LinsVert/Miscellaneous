<?php
/**
 * Created by PhpStorm.
 * User: lins
 * Date: 2018/12/29
 * Time: 16:58
 */

namespace App\Logic\Spider;

use Illuminate\Support\Facades\Redis;

/**
 * ip池维护,需要redis的支持 需要 predis/predis 包
 * Class IPCheckLogic
 * @package App\Logic\Spider
 */
class IPCheckLogic
{
    //待活跃池
    const IP_TEMP = "Ip_Temp";
    //活跃池
    const IP_POOL = "Ip_Pool";

    /**
     * 保存到redis暂存区
     * @param array $ips
     */
    public function save($ips = [])
    {
        if (is_array($ips)) {
            $ips = json_encode($ips);
            Redis::set(self::IP_TEMP, $ips);
        }
    }

    public static function check()
    {
        
    }
}