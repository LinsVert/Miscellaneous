<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //ccvt register
        Commands\Spider\Ccvt::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //ccvt 自动注册脚本
        //$schedule->command('ccvt:register start')->everyThirtyMinutes()->timezone('Asia/Shanghai')->between("9:00", "24:00")->withoutOverlapping()->appendOutputTo(storage_path('logs/ccvt-' . date("Y-m-d") . '.log'));
        //自动提交脚本
        $schedule->command('autoCommit:github')->daily()->timezone('Asia/Shanghai')->withoutOverlapping()->appendOutputTo(storage_path('logs/commit-' . date("Y-m-d") . '.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
