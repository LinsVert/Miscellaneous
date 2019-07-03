<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'linsvert',
    'namespace' => 'Linsvert\Spider\Http\Controllers',
], function (Router $router) {
    //添加log
    $router::resource('spiderLog', 'SpiderController');
    //添加Spider
    $router::resource('spider', 'SpiderController');
    //添加task
    $router::resource('task', 'SpiderController');
    //添加proxy
    $router::resource('proxy', 'SpiderController');
});
