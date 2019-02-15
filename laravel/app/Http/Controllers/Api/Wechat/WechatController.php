<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{   
    static $WXID = "";
    static $ROBOT = [
        'tuApi' => 'http://openapi.tuling123.com/openapi/api/v2',
        'userinfo' => '',
        'userinfoId' => ''
    ];
    const MUSICLIKE = "";

    public function __construct()
    {
        self::$WXID = env('WECHAT_ID');
        self::$ROBOT['userinfo'] = env('TULING_USER');
        self::$ROBOT['userinfoId'] = env('TULING_USER_ID');
    }

    public function init(Request $request)
    {
        $echostr = $request->input('echostr', false);
        if ($echostr) {
            Log::debug('wx checkIn' . date('Y-m-d H:i:s'));
            return $echostr;
        }
        $xml = $request->getContent();//获取xml请求
        Log::debug('get xml ' . $xml);
        if ($xml) {
            $xml = self::parseXML($xml);
        }
        //分发逻辑
        if (!$xml) return "";
        return self::checkXML($xml);
    }

    private function parseXML($xml)
    {
        $data = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if (is_object($data)) {
            $data = json_decode(json_encode($data));
            return $data;
        }
        return false;
    }

    private function checkXML($xml)
    {
        //逻辑
        $msgType = $xml->MsgType;
        $msg = "";
        if ($msgType === 'text') {
            //文字消息
            $keyWord = $xml->Content;
            self::checkKeyWord($xml, $keyWord);
            $msg = self::sendTuling($keyWord, 'text', $xml->FromUserName)['content'];

        } elseif ($msgType === 'image') {
            //图片消息
            // $pic = $xml->PicUrl;
            //$msg = self::sendTuling($pic,'image',$xml->FromUserName)['content'];
            $msg = 'Hello World!';
            $xml->MsgType = 'text';
        } elseif ($msgType === 'event') {
            $xml->MsgType = 'text';
            $isSubscribe = $xml->Event == 'subscribe' ? true : false;
            if ($isSubscribe) {
                $msg =
                    "序员L目前有以下小功能:
1.集成小机器人,正常聊天即可
2.网易云音乐爱好相识度,回复 【音缘】 按提示回复即可
3.Todo";
            } else {
                $msg = "";
            }
        }


        //返回消息
        $returnArray = [
            'ToUserName' => $xml->FromUserName,
            'FromUserName' => $xml->ToUserName,
            'CreateTime' => time(),
            'MsgType' => $xml->MsgType,
            'Content' => $msg,
        ];
        Log::info('debug returnarray', $returnArray);
        return self::data2Xml($returnArray);

    }

    /**
     * 发送消息给图灵机器人
     * @param $msg
     * @param string $type
     * @param $openId
     * @return mixed
     */
    private function sendTuling($msg, $type = 'text', $openId)
    {
        $data = [
            'reqType' => 0,
            "perception" => [
                'inputText' => [
                    'text' => '你好'
                ]
            ],
            'userInfo' => [
                'apiKey' => self::ROBOT['userinfo'],
                'userId' => self::ROBOT['userinfoId'],
                'groupId' => $openId,
            ]
        ];
        if ($type == 'text') {
            $data['perception']['inputText']['text'] = $msg;
        } else if ($type == 'image') {
            unset($data['perception']['inputText']);
            $data['perception']['inputImage']['url'] = $msg;
            $data['reqType'] = 1;
        }
        Log::debug('测试发送数据', $data);
        $result = self::curlSimple(self::ROBOT['tuApi'], 'post', json_encode($data));
        Log::info('post result' . $result);
        $result = json_decode($result, true);
        $msg = $result['results'][0]['values'];
        if ($result['results'][0]['resultType'] == 'text') {
            return ['MsgType' => 'text', 'content' => $msg['text']];
        } else {
            return ['MsgType' => 'image', 'content' => $msg['image']];
        }

    }

    private function curlSimple($url, $method, $post_data = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        } elseif ($method == 'get') {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 转换数组为xml.
     *
     * @param array $data 数组
     * @param string $item item的属性名
     * @param string $id id的属性名
     *
     * @return string
     */
    private static function data2Xml($data, $item = 'item', $id = 'id')
    {
        $attr = '';
        $xml = "<xml>";
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            if ((is_array($val) || is_object($val))) {
                $xml .= self::data2Xml((array)$val, $item, $id);
            } else {
                $xml .= is_numeric($val) ? $val : self::cdata($val);
            }
            $xml .= "</{$key}>";
        }
        $xml .= "</xml>";
//        Log::info('debug '.$xml);
        return $xml;
    }

    /**
     * 生成<![CDATA[%s]]>.
     *
     * @param string $string 内容
     *
     * @return string
     */
    public static function cdata($string)
    {
        return sprintf('<![CDATA[%s]]>', $string);
    }

    private function checkKeyWord($xml, $keyword)
    {
        Redis::select(1);
        $step = Redis::get('musicLike_' . $xml->FromUserName) ?: 0;
        if ($keyword == '音缘') {
            Redis::set('musicLike_' . $xml->FromUserName, 1);
            $msg = "请输入一个昵称";
        }
    }

}
