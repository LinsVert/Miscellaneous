<?php

namespace Linsvert\Spider\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Linsvert\Spider\Http\Models\TaskModel;
use Linsvert\Spider\Http\Models\SpiderModel;
use Encore\Admin\Controllers\HasResourceActions;

class TaskController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body($this->grid());
    }
    public function grid()
    {
        $grid = new Grid(new TaskModel);
        return $grid;
    }
    public function edit($id, Content $content)
    {
        return $content
        ->header('Edit')
        ->description('edit')
        ->body($this->form()->edit($id));
    }
    public function create(Content $content)
    {
        return $content
            ->title('Task')
            ->description('Create')
            ->body($this->form());
    }

    public function form(){
        $form = new Form(new TaskModel);
        //计划名称
        $form->text('name');
        //执行计划
        $form->text('crontab');
        //调度的服务器 cli模式不好获取ip 需要再确认下怎么定义
        $form->text('server')->help('//todo');
        //日志地址
        $form->text('log_path');
        //是否可以重复执行
        $form->text('withoutOverlapping');
        $form->select('spider_id', 'Spider')->options(SpiderModel::all()->pluck('name', 'id'));
        $form->ignore('server');
        $form->ignore('log_path');
        $form->ignore('withoutOverlapping');
        
        return $form;
    }

}