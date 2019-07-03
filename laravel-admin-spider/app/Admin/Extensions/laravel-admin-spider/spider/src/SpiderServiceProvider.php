<?php

namespace Linsvert\Spider;

use Illuminate\Support\ServiceProvider;

class SpiderServiceProvider extends ServiceProvider
{
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

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-spider/spider')],
                'spider'
            );
        }

        $this->app->booted(function () {
            Spider::routes(__DIR__.'/../routes/web.php');
        });
    }
}