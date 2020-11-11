<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Dcat\Admin\Layout\Content;
use App\Admin\Pages\ResultPage;

class ResultPageController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('查看结果')
            ->body(new ResultPage());
    }
}