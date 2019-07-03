# Miscellaneous
php相关项目

#### 1.ccvt投机倒把

 ##### 1.1 rename laravel/env.example 
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

 #### 2. Gayhub的定时提交

 ##### 目的在于刷新GitHub的活跃度

 ##### 2.1 切换分支到commit或者新建一个提交分支

 ##### 因自动提交会留下提交记录，不好放在master上，所以采用其他分支提交，选择好分支后需要在服务器上添加定时任务`Crontab`
 ```
    * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
  ```
  ##### 注释掉`laravel/app/Console/Kernel.php`里的自动提交命令，让命令生效
  ```
        //自动提交脚本
        $schedule->command('autoCommit:github')->daily()->timezone('Asia/Shanghai')->withoutOverlapping()->appendOutputTo(storage_path('logs/commit-' . date("Y-m-d") . '.log'));
  ```
  ##### 注意要在 laravel/.env里配置上你的`邮箱地址`以及`smtp`，都完成后每天0点就能收到自动提交的邮件了。（需要phpmailer）

  #### 3.基于laravel-admin 的自动化爬虫
  
  Todo