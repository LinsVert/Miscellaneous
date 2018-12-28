<?php

namespace App\Console\Commands\Tools;

use Illuminate\Console\Command;

class AutoCommitGitHub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoCommit:github';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'github每日自动提交';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set("Asia/Shanghai");
        $now = date('Y-m-d H:i:s');
        $file = base_path('autoCommitGithub.log');
        if (!file_exists($file)) {
            file_put_contents($file, '第一次提交，新建文件!Time:' . $now . PHP_EOL);
        }
        file_put_contents($file, "自动提交。时间：" . $now . PHP_EOL, FILE_APPEND);
        $command = "git pull && git status &&  git add -A && git commit -a -m " . "'默认提交{$now}' && git push";
        $status = 1;
        exec($command, $out, $status);
        var_dump($out);
        var_dump($status);
    }
}
