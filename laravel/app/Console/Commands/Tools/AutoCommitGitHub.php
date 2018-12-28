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
       echo app_path('');
    }
}
