<?php

namespace Linsvert\Spider\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Linsvert\Spider\Http\Models\ServersModel;
use Encore\Admin\Controllers\HasResourceActions;

class ServerController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->title('Servers')
            ->description('List')
            ->body($this->grid());
    }

    public function grid()
    {
        $grid = new Grid(new ServersModel);
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
    public function form() {
        $form = new Form(new ServersModel);
        $form->text('server_name');
        $form->text('ip');
        $form->text('status');
        return $form;
    }
}
