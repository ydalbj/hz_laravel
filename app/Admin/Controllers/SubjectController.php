<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Subject;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class SubjectController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return '问卷';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Subject(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title')->editable();
            $grid->column('description')->editable();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
                $subject_id = $this->getKey()->id;
                // append一个操作
                $actions->prepend('<a href="/admin/questions?subject_id=' . $subject_id . '"><i class="fa fa-eye"></i> 查看</a>');
            });
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
            $grid->showBatchActions();
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
        return Show::make($id, new Subject(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('description');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Subject(), function (Form $form) {
            $form->display('id');
            $form->text('title');
            $form->text('description');
        });
    }
}
