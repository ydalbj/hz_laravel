<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\GroupLevel;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Request;
use App\Models\Group;

class GroupLevelController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GroupLevel(), function (Grid $grid) {
            $group_id = Request::input('group_id');

            $grid->column('id')->sortable();
            $grid->column('group_id');
            $grid->column('level')->editable();
            $grid->column('evaluation')->editable();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
                $filter->equal('group_id', '问卷')->select(function () {
                    return Group::pluck('title', 'id')->toArray();
                });
            });
            $grid->disableCreateButton();


            $create_url = '/admin/group_levels/create?group_id=' . $group_id;
            $grid->tools('<a class="btn btn-primary disable-outline" href=' . $create_url . '>新增分组等级</a>');

            $return_url = '/admin/groups';
            $grid->tools('<a class="btn btn-primary disable-outline" href=' . $return_url . '>返回分组列表</a>');
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new GroupLevel(), function (Show $show) {
            $show->field('id');
            $show->field('group_id');
            $show->field('level');
            $show->field('evaluation');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new GroupLevel(), function (Form $form) {
            $group_id = Request::input('group_id');

            $form->display('id');
            $form->select('group_id')
                ->options(function () use ($group_id) {
                    return Group::query()->pluck('title', 'id')->toArray();
                })
                ->default($group_id);
                    
            $form->number('level')->min(0)->max(5)->default(3);
            $form->text('evaluation');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
