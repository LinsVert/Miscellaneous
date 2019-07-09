<?php

namespace Linsvert\Spider\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Linsvert\Spider\Http\Models\SpiderModel;

class SpiderController extends Controller
{
    // use HasResource;
    protected $type = [
        'xpath' => 'xpath',
        'regex' => 'regex',
        'css selector' => 'css selector',
    ];

    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body($this->grid());
    }
    public function grid() {
        $grid = new Grid(new SpiderModel);
        return $grid;
    }
    public function create(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body($this->form());
    }

    public function form(){
        //表单页
        $form = new Form(new SpiderModel);
        $form->text('spider_name', 'Spider Name');
        $form->text('url', 'Target Url');
        $form->fieldset('List', function (Form $form) {
            $form->radio('list_type', 'List Catch Func')->options($this->type);
            $form->text('list_rule', 'List Catch Rule');
        });
        $form->fieldset('Detail', function (Form $form) {
            $form->radio('detail_type', 'Detail Catch Func')->options($this->type);
            $form->text('detail_rule', 'Detail Catch Rule');
        });
        $form->checkbox('proxy', 'Use Proxy')->options([1 => 'Used']);
        $form->number('deep', 'Detail Catch Deep')->default(0)->help('This setting is used to catch max deep by target url detail page,default 0,catch all');
        return $form;
    }
}