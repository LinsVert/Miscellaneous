<?php
/**
 * Created by PhpStorm.
 * User: lins
 * Date: 2018/12/29
 * Time: 15:39
 */

namespace App\Logic\Spider;

use App\ConstDir\RequestConst;
use App\Facades\Curl;
use App\Facades\Selector;

class IPSpiderLogic
{
    //一些免费代理的IP网站
    const FREE_IP_URL = [
        '66ip' => "http://www.66ip.cn/nmtq.php?getnum=%s&isp=0&anonymoustype=0&start=&ports=&export=&ipaddress=&area=1&proxytype=2&api=66ip",
        "jianxianli" => "http://ip.jiangxianli.com/api/proxy_ip",
        "data5u" => "http://www.data5u.com/free/index.shtml",
        "xicidaili" => "http://www.xicidaili.com/",
        "kxdaili" => "http://ip.kxdaili.com/",
    ];

    /**
     * 66ip的代理池
     * @param int $num
     * @return mixed
     */
    public static function get66Ip($num = 10)
    {
        //66ip目前加了 521 js cookie跳转验证 先不用
        $url = sprintf(self::FREE_IP_URL['66ip'], $num);
        $num = 0;
        Curl::set_timeout(10);
        Curl::set_useragent(RequestConst::USER_AGENT);
        Curl::set_referer($url);
        do {
            $num++;
            $html = Curl::get($url);
            $ips = self::preIp($html);
        } while (!$ips && $num <= 5);
        return $ips;
    }

    /**
     * jianxianliIP池
     * @return array|bool
     */
    public static function getjxlIp()
    {
        $url = self::FREE_IP_URL['jianxianli'];
        $num = 0;
        do {
            $num++;
            Curl::set_error('');
            Curl::set_timeout(20);
            $poxy = Curl::get($url);
            if (!$poxy) {
                $poxy = '{"code":1}';
            }
            $poxy = json_decode($poxy);
        } while (!empty(Curl::get_error()) && $num <= 5);
        if ($poxy->code != 0) {
            return false;
        } else {
            if (isset($poxy->data->ip) && isset($poxy->data->port))
                $ips = $poxy->data->ip . ":" . $poxy->data->port;
            else return false;
            return [$ips];
        }
    }

    /**
     * @return array
     */
    public static function getjxlIpH()
    {
        $ips = [];
        $url = "http://ip.jiangxianli.com/?page=%s";
        $referer = "http://ip.jiangxianli.com";
        Curl::set_referer($referer);
        $pages = 1;
        do {
            Curl::set_useragent(RequestConst::USER_AGENT);
            $urls = sprintf($url, $pages);
            $pages++;
            $html = Curl::get($urls);
            $page = (array)Selector::select($html, "//ul[@class='pagination']/li/a");
            $total = (int)$page[count($page) - 2];
            $ip = Selector::select($html, "//tbody/tr/td[2]");
            $port = Selector::select($html, "//tbody/tr/td[3]");
            if ($ip && $port) {
                $res = array_map(function ($v1, $v2) {
                    return $v1 . ":" . $v2;
                }, (array)$ip, (array)$port);
                $ips = array_merge($ips, $res);
            }
        } while ($pages <= $total);
        return $ips;
    }

    /**
     * @return array
     */
    public static function getkxIp()
    {
        $ips = [];
        $url = "http://ip.kxdaili.com/ipList/%s.html#ip";
        $referer = "http://ip.kxdaili.com";
        Curl::set_referer($referer);
        Curl::set_useragent(RequestConst::USER_AGENT);
        $pages = 1;
        do {
            $urls = sprintf($url, $pages);
            $pages++;
            $html = Curl::get($urls);
            $page = (array)selector::select($html, "//div[@class='page']/a");
            $total = (int)$page[count($page) - 1];
            $ip = selector::select($html, "//tbody/tr/td[1]");
            $port = selector::select($html, "//tbody/tr/td[2]");
            if ($ip && $port) {
                $res = array_map(function ($v1, $v2) {
                    return $v1 . ":" . $v2;
                }, (array)$ip, (array)$port);
                $ips = array_merge($ips, $res);
            }
        } while ($pages <= $total);
        return $ips;
    }

    /**
     * @return array
     */
    public static function getxcIp()
    {
        $ips = [];
        $url = ['http://www.xicidaili.com/nn/', 'http://www.xicidaili.com/nt/', 'http://www.xicidaili.com/wn/', 'http://www.xicidaili.com/wt/'];
        $referer = "http://ip.kxdaili.com";
        Curl::set_referer($referer);
        Curl::set_useragent(RequestConst::USER_AGENT);
        $pages = 0;
        do {
            $urls = $url[$pages];
            $pages++;
            $html = Curl::get($urls);
            $ip = selector::select($html, "//tr[@class='odd']/td[2]");
            $port = selector::select($html, "//tr[@class='odd']/td[3]");
            if ($ip && $port) {
                $res = array_map(function ($v1, $v2) {
                    return $v1 . ":" . $v2;
                }, (array)$ip, (array)$port);
                $ips = array_merge($ips, $res);
            }
        } while (isset($url[$pages]));
        return $ips;
    }

    /**
     * @return array
     */
    public static function getData5uIp()
    {
        $ips = [];
        $url = ['http://www.data5u.com/free/gngn/index.shtml', 'http://www.data5u.com/free/gnpt/index.shtml', 'http://www.data5u.com/free/gwgn/index.shtml', 'http://www.data5u.com/free/gwpt/index.shtml'];
        $referer = "http://www.data5u.com/free/gwgn/index.shtml";
        Curl::set_referer($referer);
        $pages = 0;
        do {
            Curl::set_useragent(RequestConst::USER_AGENT);
            $urls = $url[$pages];
            $pages++;
            $html = Curl::get($urls);
            $ip = selector::select($html, "//ul[@class='l2']/span[1]/li");
            $port = selector::select($html, "//ul[@class='l2']/span[2]/li");
            if ($ip && $port) {
                $res = array_map(function ($v1, $v2) {
                    return $v1 . ":" . $v2;
                }, (array)$ip, (array)$port);
                $ips = array_merge($ips, $res);
            }
        } while (isset($url[$pages]));
        return $ips;
    }

    /**
     * 匹配IP
     * @param $html
     * @return bool
     */
    protected static function preIp($html)
    {
        $pre = "/\d+\.\d+\.\d+\.\d+\:\d+/";//ip 简易匹配
        preg_match_all($pre, $html, $ips);
        if (!$ips) {
            return false;
        } else {
            return $ips[0];
        }
    }

    /**
     * @return array
     */
    public static function getProxyIp()
    {
        $ip1 = self::getjxlIp() ?? [];
        $ip2 = self::getjxlIpH() ?? [];
        $ip3 = self::getData5uIp() ?? [];
        $ip4 = self::getxcIp() ?? [];
        $ip5 = self::getkxIp() ?? [];
        $ips = array_merge($ip1, $ip2, $ip3, $ip4, $ip5);
        $ips = array_unique($ips);
        return $ips;
    }

}