<?php

namespace Linsvert\Spider;

use Encore\Admin\Extension;

class Spider extends Extension
{
    public $name = 'spider';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Spider',
        'path'  => 'spider',
        'icon'  => 'fa-gears',
    ];
}