<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\WeChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/admin');

Route::get('/subject/{id}', [QuestionController::class, 'index'])->where('id', '[0-9]+');

Route::post('/subject/{id}/answer', [QuestionController::class, 'answer'])->where('id', '[0-9]+');

Route::any('/wechat', [WeChatController::class, 'serve']);