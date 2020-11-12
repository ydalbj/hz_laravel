<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Result;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class ResultController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Result(), function (Grid $grid) {
            $grid->column('id')->sortable();
            // $grid->column('subject_id');
            // $grid->column('results');
            $grid->column('score');
            $grid->column('age');
            $sex_options = config('question.sex');
            $grid->column('sex')->display(function ($sex) use ($sex_options) {
                return $sex_options[$sex];
            });
            $grid->column('created_at', '提交时间')->sortable();
            // $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) use ($sex_options) {
                // $filter->equal('id');
                $filter->equal('age');

                $filter->equal('sex')->radio($sex_options);
            });

            $grid->column('查看结果')->display(function () {
                $result = $this->getKey();
                $subject_id = $result->subject_id;
                $id = $result->id;
                $view_url = '/admin/result/page?id=' . $id . '&subject_id=' . $subject_id;
                return "<a href='{$view_url}' target='_blank'><i class='fa fa-eye'></i>查看</a>";
            });
            $grid->disableActions();
            $grid->disableBatchActions();
            $grid->disableCreateButton();
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
        return Show::make($id, new Result(), function (Show $show) {
            $show->field('id');
            $show->field('subject_id');
            $show->field('results');
            $show->field('score');
            $show->field('age');
            $show->field('sex');
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
        return Form::make(new Result(), function (Form $form) {
            $form->display('id');
            $form->text('subject_id');
            $form->text('results');
            $form->text('score');
            $form->text('age');
            $form->text('sex');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
