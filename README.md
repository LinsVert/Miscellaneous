# Miscellaneous
什么都有

#### 1.ccvt投机倒把

 ##### 1.1 rename env.example 
 修改添加里面需要的账号信息
 
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.163.com
MAIL_PORT=2525
MAIL_USERNAME=smtp用户名
MAIL_PASSWORD=密码
MAIL_ENCRYPTION=null

YI_MA_USERNAME = 易码用户名
YI_MA_USERNAME = 易码密码
YI_MA_TOKEN = 易码token
INIVT_CODE = ccvt邀请码
BAIDU_CLIENT_ID = 百度文字识别key
BAIDU_CLIENT_SECRET = 百度文字识别secret
MAIL_TO = 目标邮箱
MAIL_FROM = 发送邮箱

```
 ##### 1.2 执行 composer require phpmailer/phpmailer
 ##### 1.3 执行 composer install
 ##### 1.4 执行 在目录下执行 php artisan ccvt:register start
 ##### 1.5 如果需要 使用定时 需要在 服务器的定时任务中加入 
 
  ```
    * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
  ```
 并修改 app/Console/Kernel.php 取消ccvt命令注释 并选择执行时间与周期