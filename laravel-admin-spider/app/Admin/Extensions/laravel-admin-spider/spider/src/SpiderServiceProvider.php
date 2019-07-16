<?php

namespace Linsvert\Spider;

use Illuminate\Support\ServiceProvider;

class SpiderServiceProvider extends ServiceProvider
{
    protected $commands = [
        \Linsvert\Spider\Console\TaskCommand::class,
        \Linsvert\Spider\Console\TestCommand::class,
    ];
    /**
     * {@inheritdoc}
     */
    public function boot(Spider $extension)
    {
        if (! Spider::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'spider');
        }
         //数据迁移
         if ($migrations = $extension->migrations()) {
            $this->loadMigrationsFrom($migrations);
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-spider/spider')],
                'spider'
            );
        }

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
        $this->app->booted(function () {
            Spider::routes(__DIR__.'/../routes/web.php');
        });
    }
}