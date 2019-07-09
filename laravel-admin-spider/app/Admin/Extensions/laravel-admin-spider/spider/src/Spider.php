<?php

namespace Linsvert\Spider;

use Encore\Admin\Extension;

class Spider extends Extension
{
    public $name = 'spider';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $migrations = __DIR__.'/../database/migrations';

    public $menu = [
        'title' => 'Spider',
        'path'  => 'linsvert/spider',
        'icon'  => 'fa-gears',
    ];
}