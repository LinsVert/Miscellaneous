<?php
/**
 * 请求类的服务提供者
 */

namespace App\Providers;

use App\Libraries\Request;
use Illuminate\Support\ServiceProvider;

class CurlServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Curl', function () {
            return new Request();
        });
    }

    /**
     * 获取由提供者提供的服务.
     *
     * @return array
     */
    public function provides()
    {
        return [Request::class];
    }
}
