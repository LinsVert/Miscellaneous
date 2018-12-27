<?php

namespace App\Console\Commands\Spider;

use App\Facades\Curl;
use App\Libraries\Mail;
use App\Libraries\Ocr\BaiduOcr;
use Illuminate\Console\Command;

class Ccvt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ccvt:register {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ccvt邀请刷ccvt币';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    //易码token
    public $token = "";
    //易码登录地址
    public $login_url = "http://api.fxhyd.cn/UserInterface.aspx?action=login&username=%s&password=%s";
    //用户信息接口
    public $userinfo_url = "http://api.fxhyd.cn/UserInterface.aspx?action=getaccountinfo&token=%s";
    //获取手机号接口
    public $Telephone_Api = "http://api.fxhyd.cn/UserInterface.aspx?action=getmobile&token=%s&itemid=%s";
    //释放手机号接口
    public $Free_Telephone_Api = "http://api.fxhyd.cn/UserInterface.aspx?action=release&token=%s&itemid=%s&mobile=%s";
    //项目编号 风赢科技
    public $item_num = 29622;
    //获取短信接口 因短信可能延迟，建议每5秒调用一次，调用60秒以上（可增加获取成功率）。
    public $GetMessage_Api = "http://api.fxhyd.cn/UserInterface.aspx?action=getsms&token=%s&itemid=%s&mobile=%s&release=1";
    //注册接口
    public $register_url = "https://ccvt.io/api/user/reg_phone.php?callback=&country_code=86&cellphone=%s&sms_code=%s&pass_word=qwe12345&pass_word_hash=2b22b6d7dafa0d9c8fd74a387d76b87137b9584e&invit_code=%s&_=%s";
    //图形验证码接口
    public $code_url = "https://ccvt.io/api/inc/code.php";
    //发送验证码 cfm_code 图形验证码
    public $send_sms = "https://ccvt.io/api/user/sms_send.php?callback=&cellphone=%s&country_code=86&bind_type=1&cfm_code=%s&_=%s";
    //referer
    public $referer = "https://ccvt.io/h5/user/register.html";

    public $useragent = [
        "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Mobile Safari/537.36"
    ];
    const CCVT_URL = 'ccvt.io';

    const MONEY_MIN = 10;
    //mail 邮件任务
    static $mail_password = "";
    static $mail_username = "";
    static $mail_to = "";
    static $mail_to_name = "admin";
    static $mail_from = "";
    static $mail_from_name = "system";
    //数据缓存区
    static $session = '';
    static $yima_telephone = '';
    static $code = '';
    static $sms_code = '';
    static $invit_code = 0;

    /**
     * Ccvt constructor.
     */
    public function __construct()
    {
        parent::__construct();;
        if ($this->useragent) {
            Curl::set_useragent($this->useragent);
        }
        if ($this->referer) {
            Curl::set_referer($this->referer);
        }
        $this->login_url = sprintf($this->login_url, env('YI_MA_USERNAME'), env('YI_MA_USERNAME'));
        $this->token = env('YI_MA_TOKEN');
        self::$mail_password = env('MAIL_PASSWORD');
        self::$mail_username = env('MAIL_USERNAME');
        self::$mail_to = env('MAIL_TO');
        self::$mail_from = env('MAIL_FROM');
        self::$invit_code = env('INIVT_CODE');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        //showTime
        $action = $this->argument('action');
        switch ($action) {
            case 'start':
                //正式
                $this->start();
                break;
            case 'sendEmail':
                //测试 pass
                break;
            case 'checkAccountMoney':
                $this->checkAccountMoney();
                //获取余额
                break;
            case 'getTelephone':
                $this->getTelephone();
                break;
            case 'sendSmsCode':
                $this->sendSmsCode();
                break;
            case 'getSmsCode':
                $this->getSmsCode();
                break;
            case 'freeTelephone':
                self::$yima_telephone = 15197884143;
                $this->freeTelephone();
                break;
            default:
                //todo
                break;
        }
        return 'run success';
    }

    public function start()
    {
        //show Time
        //1。拿手机号
        //2。验证验证码
        //3。发送短信验证码
        //4。轮询接口拿短信验证码
        //5。注册
        if ($this->checkAccountMoney()) {
            //code 重试5次
            while (!self::$code) {
                $this->getAuthCode();
            }
            $this->getTelephone();
            $this->sendSmsCode();
            $this->getSmsCode();
            $this->register();
        }
        //异常： 代理ip在第n步挂了怎么办？ sessionId 会变吗？
        //所以验证码 可以提前到第一步 然后 全部保存这个session

    }

    /**
     * 获取短信验证码
     */
    public function getSmsCode()
    {
        //需要轮询
        $getUrl = sprintf($this->GetMessage_Api, $this->token, $this->item_num, self::$yima_telephone);
        $nowTime = time();
        $endTime = $nowTime + 60;
        do {
            if ($endTime < time()) {
                //超时
                break;
            }
            $result = Curl::get($getUrl);
            if ($result) {
                $_result = explode('|', $result);
                if ($_result[0] == 'success') {
                    $content = $_result[1];
                    echo "短信获取成功:" . $content . PHP_EOL;
                    //释放
                    if (preg_match("/\d+/", $content, $codes)) {
                        self::$sms_code = $codes[0];
                    } else {
                        $this->sendEmail('脚本告警', "获取验证码失败! :</br>" . $content, true);
                        $this->freeTelephone();
                        break;
                    }
                    $this->freeTelephone();
                    break;
                } elseif ($_result[0] != 3001) {
                    //不是3001状态的直接不要这次的注册 发送邮件
                    $this->freeTelephone();
                    $this->sendEmail('脚本告警', "轮询手机号码异常！返回内容如下:</br>" . $result, true);
                    break;
                }
            }
            sleep(5);
        } while (!self::$sms_code);
    }

    /**
     * 释放手机号
     */
    public function freeTelephone()
    {
        $getUrl = sprintf($this->Free_Telephone_Api, $this->token, $this->item_num, self::$yima_telephone);
        echo "手机号释放:" . Curl::get($getUrl) . PHP_EOL;
    }

    /**
     * 拿易码平台的账户余额
     * return bool
     */
    public function checkAccountMoney()
    {
        $getUrl = sprintf($this->userinfo_url, $this->token);
        $result = Curl::get($getUrl);
        if ($result) {
            $_result = explode('|', $result);
            if ($_result[0] != 'success') {
                //发送邮件告警
                $this->sendEmail('脚本告警', "获取用户信息失败! 接口返回内容:</br>" . $result, true);
                return false;
            } else {
                //第5项是余额
                $money = $_result[4] * 100;
                if ($money <= self::MONEY_MIN) {
                    $this->sendEmail('脚本告警', "User Money None:</br>" . $result, true);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 获取手机号码
     */
    public function getTelephone()
    {
        $getUrl = sprintf($this->Telephone_Api, $this->token, $this->item_num);
        $result = Curl::get($getUrl);
        if ($result) {
            $results = explode('|', $result);
            if ($results[0] == 'success') {
                if (is_numeric($results[1])) {
                    self::$yima_telephone = $results[1];
                } else {
                    //发送邮件告警
                    $this->sendEmail('脚本告警', "获取yima手机号失败 code 2!接口返回内容:</br>" . $result, true);
                    exit;
                }
            } else {
                //发送邮件告警
                $this->sendEmail('脚本告警', "获取yima手机号失败 code 1!接口返回内容:</br>" . $result, true);
                exit;
            }
        }
    }

    /**
     * 发送邮件
     * @param $subject
     * @param $body
     * @param bool $isHtml
     */
    public function sendEmail($subject, $body, $isHtml = false)
    {
        $mail = new Mail(self::$mail_username, self::$mail_password);
        $mail->sendMail(self::$mail_from, self::$mail_from_name, self::$mail_to, self::$mail_to_name, $subject, $body, $isHtml);
    }

    /**
     * 发送验证码
     */
    public function sendSmsCode()
    {
        $getUrl = sprintf($this->send_sms, self::$yima_telephone, self::$code, time() * 1000);
        $errorTime = 10;
        $start = 0;
        Resend:
        Curl::set_referer($this->referer);
        $result = Curl::get($getUrl);
        if ($result) {
            $_result = str_replace("(", '', $result);
            $_result = str_replace(');', '', $_result);
            $_result = json_decode($_result) ?? '';
            if (isset($_result->errcode) && $_result->errcode != 0) {
                $this->freeTelephone();
                $this->sendEmail('脚本告警', "验证码发送失败: 内容如下:</br>" . "url:" . $getUrl . "</br>返回内容:" . $result, true);
                exit;
            } elseif (!isset($_result->errcode)) {
                if ($start < $errorTime) {
                    $start++;
                    goto Resend;
                }
                $this->freeTelephone();
                $this->sendEmail('脚本告警', "验证码发送失败3: 内容如下:</br>" . "url:" . $getUrl . "</br>返回内容:" . $result, true);
                exit;
            }
        } else {
            if ($start < $errorTime) {
                $start++;
                goto Resend;
            }
            $this->freeTelephone();
            $this->sendEmail('脚本告警', "验证码发送失败2: 返回数据为空 内容如下:</br>" . "url:" . $getUrl . "</br>返回内容:" . $result, true);
            exit;
        }
    }

    /**
     * 获取图片验证码
     */
    public function getAuthCode()
    {
        //这个请求会生成唯一的 sessionId 需要注意接口异常问题
        $errorTime = 10;
        $start = 0;
        Resend:
        //todo 获取代理ip
        $ip = "";
        if ($ip) {
            Curl::set_proxy($ip);
        }
        Curl::set_referer($this->referer);
        $code = Curl::get($this->code_url);
        if ($code) {
            $cookie = Curl::get_cookies(self::CCVT_URL);
            if (!$cookie) {
                if ($start < $errorTime) {
                    $start++;
                    goto Resend;
                }
                $this->sendEmail('脚本告警', "获取cookie失败" . date("Y-m-d H:i:s"));
                exit;
            } else {
                self::$session = $cookie;
                $this->downloadCode($code);
                $this->getCode();
            }
        }
    }

    /**
     * 5.注册
     */
    public function register()
    {
        if ($this->checkParam()) {
            $getUrl = sprintf($this->register_url, self::$yima_telephone, self::$sms_code, self::$invit_code, time() * 100);
            $result = Curl::get($getUrl);
            if ($result) {
                $_result = str_replace("(", '', $result);
                $_result = str_replace(');', '', $_result);
                $_result = json_decode($_result) ?? '';
                if (isset($_result->errcode) && $_result->errcode == 0) {
                    //注册成功
                    $this->sendEmail('注册成功通知', "使用的手机号为:</br>" . self::$yima_telephone . "注册时间:</br>" . date("Y-m-d H:i:s"), true);
                } else {
                    echo "注册失败!" . date('Y-m-d H:i:s') . PHP_EOL;
                    echo "使用手机号:" . self::$yima_telephone . "使用验证码:" . self::$sms_code . PHP_EOL;
                    echo "返回数据结构:" . json_encode($_result, JSON_UNESCAPED_UNICODE) . PHP_EOL;
                }
            }
        }

    }

    /**
     * 校验必要的参数
     * @return bool
     */
    private function checkParam()
    {
        if (!self::$yima_telephone) {
            return false;
        }
        if (!self::$sms_code) {
            return false;
        }
        return true;
    }

    /**
     * 保存验证码图片
     * @param $image
     */
    public function downloadCode($image)
    {
        $file = app_path('Storage/ccvt/code/');
        if (!is_dir($file)) {
            mkdir($file, 0777, true);
        }
        $file .= 'code.png';
        file_put_contents($file, $image);
    }

    /**
     * 获取验证码
     */
    public function getCode()
    {
        //通过百度ocr接口拿验证码存在一定的准确率
        $file = app_path('Storage/ccvt/code/');
        $file = $file . "code.png";
        if (!file_exists($file)) {
            $this->sendEmail('脚本告警', "获取验证码图片失败!" . date("Y-m-d H:i:s"));
        } else {
            $ocr = new BaiduOcr();
            $code = $ocr->getCode($file);
            if (is_numeric($code)) {
                self::$code = $code;
            } else {
                $this->sendEmail('脚本告警', "百度接口识别错误!返回内容:</br>" . json_encode($code, JSON_UNESCAPED_UNICODE), true);
                exit;
            }
        }
    }
}
