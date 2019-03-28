<?php

namespace App\Http\Controllers;

use App\Libraries\Request\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        Request::getInstance()->test();
    }
}