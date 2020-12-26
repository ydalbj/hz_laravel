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
            $question_id = Request::input('question_id');
            $subject_id = Request::input('subject_id');

            $grid->column('id')->sortable();
            // $grid->column('question_id');
            $grid->column('title')->editable();
            // $grid->column('score')->editable();
            $grid->column('is_selected_pass', '选中后继续(针对`以上都做不到`的选项)')->switch();


            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
            });
        
            $grid->filter(function (Grid\Filter $filter) use ($question_id) {
                $filter->panel();
                // $filter->equal('id');
        
                $filter->equal('question_id', '问题')->select(function () use ($question_id) {
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

            $grid->disableCreateButton();
            $create_url = "/admin/answers/create?question_id=$question_id&subject_id=$subject_id";
            $grid->tools('<a class="btn btn-primary disable-outline" href=' . $create_url . '>新增答案选项</a>');

            $return_url = '/admin/questions?subject_id=' . $subject_id  ;
            $grid->tools('<a class="btn btn-primary disable-outline" href=' . $return_url . '>返回问题列表</a>');
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
            $show->field('is_selected_pass');
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
            $question_id = Request::input('question_id');
            $subject_id = Request::input('subject_id');
            $form->display('id');
            // $form->text('question_id')->value($question_id);
            $form->select('question_id')
                ->options(function () use ($question_id) {
                    $question = Question::find($question_id);
                    return [$question->id => $question->title];
                })
                ->default($question_id);
                
            $form->text('title')->required();
            // $form->text('score')->required()->default(0);
            $form->radio('is_selected_pass', '选中后继续(针对`以上都做不到`的选项)')->options([0=>'否', 1=>'是'])->default(0)->required();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableList();
                // $tools->disableView();
            });

            $form->footer(function ($footer) {
                $footer->disableViewCheck();
                $footer->disableEditingCheck();
                $footer->disableCreatingCheck();
            });

            $return_url = "/admin/answers?question_id=$question_id&subject_id=$subject_id";
            $form->tools('<a class="btn btn-primary disable-outline" href=' . $return_url . '>返回</a>');
        });
    }
}
