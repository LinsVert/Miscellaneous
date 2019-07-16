<?php

namespace Linsvert\Spider\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Linsvert\Spider\Http\Forms\Others;
use Linsvert\Spider\Http\Models\SpiderModel;
use Encore\Admin\Controllers\HasResourceActions;

class OtherController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->title('Other')
            ->description('some tools')
            ->body($this->tab());
    }

    public function tab()
    {
        return Tab::forms([
            'publish' => Others\Publish::class,
        ]);
    }
}