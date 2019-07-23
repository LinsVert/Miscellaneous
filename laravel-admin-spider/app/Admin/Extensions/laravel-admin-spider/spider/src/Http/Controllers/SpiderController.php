<?php

namespace Linsvert\Spider\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Linsvert\Spider\Http\Models\SpiderModel;
use Encore\Admin\Controllers\HasResourceActions;

class SpiderController extends Controller
{
    use HasResourceActions;

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
    public function grid()
    {
        $grid = new Grid(new SpiderModel);
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
            ->title('Spider')
            ->description('Create')
            ->body($this->form());
    }

    public function form(){
        //表单页
        $form = new Form(new SpiderModel);
        //修改表单样式符合使用的phpspider 框架构造
        // 此配置为基本配置 具体调度需要task的配合(相当于定义爬虫爬取的方法)
        $form->tab('Base config', function ($form) {
            $form->text('name', 'SpiderName')->rules('required');
            $form->number('tasknum', 'TaskNum')->default(1)->rules('required')->help('多进程设置, 如果大于1需要配合redis使用,供进程间共享使用');
            $form->radio('log_show')->options([0 => 'False', 1 => 'True'])->help('当使用cli命令调用的时候会显示日志');
            $form->radio('multiserver')->options([0 => 'False', 1 => 'True'])->help('多服务器处理,需要配合redis来保存采集任务数据，供多服务器共享数据使用');
        });
        $form->tab('Scan Settings', function ($form) {
            $form->html('定义爬虫爬取哪些域名下的网页, 非域名下的url会被忽略以提高爬取速度');
            $form->list('domains');
            $form->divider();
            $form->html('定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接');
            $form->list('scan_urls');
        });
        $form->tab('Url Regexes', function ($form) {
            $form->html('定义列表页url的规则</br>对于有列表页的网站, 使用此配置可以大幅提高爬虫的爬取速率</br>列表页是指包含内容页列表的网页 比如http://www.qiushibaike.com/8hr/page/2/?s=4867046 就是糗事百科的一个列表页');
            $form->list('list_url_regexes');
            $form->divider();
            $form->html('定义内容页url的规则</br>内容页是指包含要爬取内容的网页 比如http://www.qiushibaike.com/article/115878724 就是糗事百科的一个内容页');
            $form->list('content_url_regexes');
        });
        $form->tab('Export Setting', function ($form) {
            $form->embeds('export_config', 'Export', function ($form) {
                $type = [
                    'csv' => 'csv',
                    'sql' => 'sql',
                    'db' => 'db'
                ];
                $form->select('type')->options($type)->help('导出类型 csv、sql、db');
                $form->text('file')->help('导出 csv、sql 文件地址');
                $form->text('table')->help('导出db、sql数据表名');
            });

            $form->embeds('db_config', 'Db Config', function ($form) {
                $form->text('host');
                $form->text('port')->default(3306);
                $form->text('user');
                $form->text('pass');
                $form->text('name');
            });
        });
        $type = $this->type;
        $form->tab('Fields', function ($form) use ($type) {
            $form->html('
<pre>
每一个field定义一个抽取项,一个field可以定义下面这些东西
name
给此项数据起个变量名
selector
定义抽取规则, 默认使用xpath
selector_type
抽取规则的类型目前可用xpath, jsonpath, regex
默认xpath
</pre>');
            $form->table('fields', 'Field', function ($table) use ($type) {
                $table->text('name');
                $table->text('selector');
                $table->select('selector_type')->options($type)->default('xpath');
            });
        });
        return $form;
    }
    
    public function store(Request $request)
    {
        dd($request->all());
    }
}