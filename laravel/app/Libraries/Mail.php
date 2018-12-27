<?php
/**
 * Created by PhpStorm.
 * User: lins
 * Date: 2018/12/18
 * Time: 23:18
 */

namespace App\Libraries;


use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    static $port = 465;
    static $username = '';
    static $password = '';
    static $SMTPSecure = '';
    static $Host = '';

    /**
     * Mail constructor.
     * @param string $host
     * @param int $port
     * @param $username
     * @param $password
     * @param string $SMTPSecure
     */
    public function __construct($username, $password, $host = 'smtp.163.com', $port = 465, $SMTPSecure = 'ssl')
    {
        self::$Host = $host;
        self::$port = $port;
        self::$username = $username;
        self::$password = $password;
        self::$SMTPSecure = $SMTPSecure;
    }

    /**
     * 发送邮件
     * @param $from
     * @param $fromName
     * @param $to
     * @param $toName
     * @param $subject
     * @param $body
     * @param bool $isHtml
     */
    public function sendMail($from, $fromName, $to, $toName, $subject, $body, $isHtml = false)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = self::$Host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;
            $mail->Port = self::$port;
            $mail->Username = self::$username;
            $mail->Password = self::$password;
            $mail->SMTPSecure = self::$SMTPSecure;     // Enable SMTP authentication             // SMTP password
            //Recipients
            $mail->setFrom($from, $fromName);
            $mail->addAddress($to, $toName);//收件人地址和姓名

            if ($isHtml) {
                $mail->isHTML(true);
            }
            $mail->Subject = $subject;//标题
            $mail->Body = $body;//正文
            $mail->send();
            echo 'Message has been sent';
        } catch (\Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

    }

}