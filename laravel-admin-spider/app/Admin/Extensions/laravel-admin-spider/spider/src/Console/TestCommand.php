<?php

namespace LinsVert\Spider\Console;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'linsvert:test';

    protected $description = 'laravel tests 调度中心';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {
        $ip_cmd = "ifconfig eth0 | sed -n '/inet addr/p' | awk '{print $2}' | awk -F ':' '{print $2}'";
$ret = trim(exec($ip_cmd));
dd($ret);
        echo time();
    }
}