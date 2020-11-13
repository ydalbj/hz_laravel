<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class WeChatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.');

        $app = app('wechat.official_account');
        $app->server->push(function ($message) {
            return "欢迎！欢迎！";
        });

        return $app->server->serve();
    }
}
