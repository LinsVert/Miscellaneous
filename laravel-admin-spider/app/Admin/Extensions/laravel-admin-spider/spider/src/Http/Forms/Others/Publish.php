<?php

namespace Linsvert\Spider\Http\Forms\Others;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Publish extends Form
{
    public $title = 'Publish';

    protected $site = [
        'JueJin' => 'JueJin',
        'JianShu' => 'JianShu',
        'Zhihu' => 'Zhihu'
    ];

    public function handle(Request $request)
    {
        admin_success('Publish was add in job, please wait call back by the message');
        return back();
    }

    public function form()
    {
        //目前一键发布功能 只有掘金比较好模拟登陆 其他的几个网站都是要验证码的 需要破解再处理该东西
        $this->html('<h4 style="color:red">This usage need used python</h4>');
        $this->checkbox('publish_site', 'Publish Target Site')->options($this->site)->help('please choose the publish site');
        $this->text('url', 'File url');
        $this->file('file', 'File local');
        $this->textarea('text', 'File online');
    }

     /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return [

        ];
    }
}