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
        echo time();
    }
}