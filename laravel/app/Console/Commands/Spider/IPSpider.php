<?php

namespace App\Console\Commands\Spider;

use App\Logic\Spider\IPCheckLogic;
use App\Logic\Spider\IPSpiderLogic;
use Illuminate\Console\Command;

class IPSpider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ipSpider:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '代理ip爬虫';

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
//        $ips = IPSpiderLogic::getProxyIp();
        IPCheckLogic::check();
//        var_dump($ips);
    }
}
