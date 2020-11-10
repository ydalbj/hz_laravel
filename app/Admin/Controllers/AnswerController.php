<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Answer;
use App\Models\Question;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Request;

class AnswerController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return '答案选项设置';
    }
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Answer(), function (Grid $grid) {
            $grid->column('id')->sortable();
            // $grid->column('question_id');
            $grid->column('title')->editable();
            $grid->column('score')->editable();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
            });
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                // $filter->equal('id');
        
                $filter->equal('question_id')->select(function () {
                    $question_id = Request::input('question_id');
                    if (!$question_id) {
                        return '';
                    }

                    $question = Question::find($question_id);
                    if (!$question) {
                        return '';
                    }

                    return [$question->id => $question->title];
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
        return Show::make($id, new Answer(), function (Show $show) {
            $show->field('id');
            $show->field('question_id');
            $show->field('title');
            $show->field('score');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Answer(), function (Form $form) {
            $form->display('id');
            $form->text('question_id');
            $form->text('title');
            $form->text('score');
        });
    }
}
