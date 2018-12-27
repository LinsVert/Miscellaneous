<?php
/**
 * Created by PhpStorm.
 * User: lins
 * Date: 2018/12/18
 * Time: 21:19
 */

namespace App\Libraries\Ocr;


use App\Facades\Curl;

class BaiduOcr
{
    static $token = '';
    const TOKEN_URL = "https://aip.baidubce.com/oauth/2.0/token";
    const OCR_NUMBER_URL = "https://aip.baidubce.com/rest/2.0/ocr/v1/numbers";
    static $client_id = "";
    static $client_secret = "";
    const grant_type = "client_credentials";

    public function __construct()
    {
        self::$client_id = env('BAIDU_CLIENT_ID');
        self::$client_secret = env('BAIDU_CLIENT_SECRET');
    }

    /**
     * 获取百度云的Access Token
     * @return string
     */
    public function getAccessToken()
    {
        if (!self::$token) {
            $param = [
                'grant_type' => self::grant_type,
                'client_id' => self::$client_id,
                'client_secret' => self::$client_secret,
            ];
            $result = Curl::post(self::TOKEN_URL, $param);
            if ($result) {
                $result = json_decode($result);
                self::$token = $result->access_token ?? '';
            }
        }
        return self::$token;
    }

    public function getCode($path)
    {
        if (!$path) {
            return false;
        }
        Curl::set_header('Content-Type', 'application/x-www-form-urlencoded');
        $image = $this->base64Image($path);
        $postUrl = self::OCR_NUMBER_URL . '?access_token=' . $this->getAccessToken();
        $param = [
            'image' => $image,
        ];
        $result = Curl::post($postUrl, $param);
        $code = '';
        if ($result) {
            $result = json_decode($result);
            if (isset($result->words_result)) {
                foreach ($result->words_result as $key => $value) {
                    $code .= $value->words;
                }
                $code = (int)$code;
            } else {
                return $result;
            }
        }
        return $code;
    }

    /**
     * @param $path
     * @return string
     */
    public function base64Image($path)
    {
        if ($fp = fopen($path, "rb", 0)) {
            $gambar = fread($fp, filesize($path));
            fclose($fp);
            $base64 = base64_encode($gambar);
            return $base64;
        }
        return '';
    }
}