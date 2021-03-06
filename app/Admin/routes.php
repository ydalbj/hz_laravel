<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/result/page', 'ResultPageController@index');

    $router->resource('subjects', 'SubjectController');

    $router->resource('questions', 'QuestionController');

    $router->resource('answers', 'AnswerController');

    $router->resource('results', 'ResultController');

    $router->resource('groups', 'GroupController');

    $router->resource('group_levels', 'GroupLevelController');
});
