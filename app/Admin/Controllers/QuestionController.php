<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Group as RepositoriesGroup;
use App\Admin\Repositories\Question;
use App\Common\AgeHelper;
use App\Common\UnicodeHelper;
use App\Models\Subject;
use App\Models\Answer;
use App\Models\Group;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Request;

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
        return Grid::make(new Question(['answers']), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            // $grid->column('subject_id');
            // $grid->column('name');
            $grid->column('title')->editable();
            $grid->column('is_required')->switch();
            // $grid->column('is_hide');
            $types = config('question.type');
            $grid->column('type')->select($types);
            $subject_id = Request::input('subject_id');
            $grid->answers('选项设置')->display(function($answers) use ($subject_id) {
                $count = count($answers);

                $data = $this->getKey();
                $question_id = $data->id;
                $view_url = "/admin/answers?question_id=$question_id&subject_id=$subject_id";
                return "<a class='disable-outline' href='{$view_url}'>{$count}个选项</a>";
            });

            $grid->column('group_id', '分组')->select(function ($group) use ($subject_id) {
                return (new RepositoriesGroup())->getGroupsBySubjectId($subject_id);
            });

            $grid->column('min_age', '最小适用月龄(-1代表无限制)')
                ->display(function ($age) {
                    return AgeHelper::monthInt2String($age);
                })
                ->editable();
            $grid->column('max_age', '最小适用月龄(-1代表无限制)')
                ->display(function ($age) {
                    return AgeHelper::monthInt2String($age);
                })
                ->editable();
            $grid->column('for_sex', '适用性别')->select(function () {
                return [
                    -1 => '无限制',
                    0 => '女孩',
                    1 => '男孩',
                ];
            });
            $grid->combine('适用年龄/性别', ['min_age', 'max_age', 'for_sex']);

            $grid->actions(function (Grid\Displayers\Actions $actions) use ($subject_id) {
                $actions->disableView();
                $actions->disableEdit();

                $data = $this->getKey();
                $question_id = $data->id;
                $action_url = "/admin/answers?question_id=$question_id&subject_id=$subject_id";
                $actions->prepend('<a href="' . $action_url . '"><i class="fa fa-eye"></i> 查看</a>');
            });
        
            $grid->filter(function (Grid\Filter $filter) use ($subject_id) {
                $filter->panel();
                // $filter->equal('id');
        
                $filter->equal('subject_id', '问卷')->select(function () {
                    return Subject::pluck('title', 'id')->toArray();
                });

                $filter->equal('group_id', '分组')->select(function () use ($subject_id) {
                    return (new RepositoriesGroup())->getGroupsBySubjectId($subject_id);
                });
            });

            $grid->disableCreateButton();
            $create_url = '/admin/questions/create?subject_id=' . $subject_id;
            $grid->tools('<a class="btn btn-primary disable-outline" href=' . $create_url . '>新增题目</a>');

            $return_url = '/admin/subjects';
            $grid->tools('<a class="btn btn-primary disable-outline" href=' . $return_url . '>返回问卷列表</a>');
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
            $show->field('group_id');
            $show->field('name');
            $show->field('title');
            $show->field('is_required');
            $show->field('is_hide');
            $show->field('type');
            $show->field('min_age');
            $show->field('max_age');
            $show->field('for_sex');
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
            $subject_id = Request::input('subject_id');
            $form->display('id');
            // $form->text('subject_id')->value($subject_id)->required();
            $form->select('subject_id')
                ->options(function () use ($subject_id) {
                    $subject = Subject::find($subject_id);
                    return [$subject->id => $subject->title];
                })
                ->default($subject_id);
            $form->select('group_id', '所属分组')
                ->options(function () use ($subject_id) {
                    return (new RepositoriesGroup())->getGroupsBySubjectId($subject_id);
                })
                ->default(0);
            $form->hidden('name')->value('test');
            $form->text('title')->required();
            $form->radio('is_required')->options([0=>'否', 1=>'是'])->default(1)->required();
            $form->hidden('is_hide')->value(0);
            $types = config('question.type');
            $form->radio('type')->options($types)->required();
            $form->text('min_age');
            $form->text('max_age');
            $form->text('for_sex'); //todo
        
            $form->display('created_at');
            $form->display('updated_at');
            
            $form->tools(function (Form\Tools $tools) {
                $tools->disableList();
                // $tools->disableView();
            });

            $form->footer(function ($footer) {
                $footer->disableViewCheck();
                $footer->disableEditingCheck();
                $footer->disableCreatingCheck();
            });

            $return_url = '/admin/questions?subject_id=' . Request::input('subject_id');
            $form->tools('<a class="btn btn-primary disable-outline" href=' . $return_url . '>返回</a>');
        });
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 删除关联答案
        Answer::where('question_id', $id)->delete();
        return parent::destroy($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $min_age = Request::input('min_age');
        $max_age = Request::input('max_age');

        $data = [];
        if ($min_age) {
            $data['min_age'] = AgeHelper::string2MonthInt($min_age);
        }

        if ($max_age) {
            $data['max_age'] = AgeHelper::string2MonthInt($max_age);
        }
        return $this->form()->update($id, $data);
    }
}
