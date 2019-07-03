<?php

namespace Linsvert\Spider\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class SpiderController extends Controller
{
    // use HasResource;
    
    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body(view('spider::index'));
    }
    public function grid() {
        //列表页
        return '';
    }
    public function form(){
        //表单页
        return '';
    }
}