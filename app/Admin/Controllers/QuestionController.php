<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Question;
use App\Models\Subject;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class QuestionController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return '题目';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Question(), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            // $grid->column('subject_id');
            // $grid->column('name');
            $grid->column('title')->editable();
            $grid->column('is_required')->switch();
            // $grid->column('is_hide');
            $types = config('question.type');
            $grid->column('type')->select($types);
            // $grid->column('created_at');
            // $grid->column('updated_at')->sortable();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();

                $data = $this->getKey();
                $question_id = $data->id;
                $actions->prepend('<a href="/admin/answers?question_id=' . $question_id . '"><i class="fa fa-eye"></i> 查看</a>');
            });
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                // $filter->equal('id');
        
                $filter->equal('subject_id')->select(function () {
                    return Subject::pluck('title', 'id')->toArray();
                });
            });
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
        return Show::make($id, new Question(), function (Show $show) {
            $show->field('id');
            $show->field('subject_id');
            $show->field('name');
            $show->field('title');
            $show->field('is_required');
            $show->field('is_hide');
            $show->field('type');
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
        return Form::make(new Question(), function (Form $form) {
            $form->display('id');
            $form->text('subject_id');
            $form->text('name');
            $form->text('title');
            $form->text('is_required');
            $form->text('is_hide');
            $form->text('type');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
