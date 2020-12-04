<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Group;
use App\Models\Group as ModelsGroup;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use App\Models\Subject;
use Illuminate\Support\Facades\Request;

class GroupController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Group(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title')->editable();
            $subject_id = Request::input('subject_id');
            $query = Subject::query();
            if ($subject_id) {
                $query = $query->where('id', $subject_id);
            }
            
            $subjects = $query->pluck('title', 'id')->toArray();
            $grid->column('subject_id', '所属主题')->select($subjects);

            $grid->levels('等级设置')->display(function ($leves) {
                $data = $this->getKey();
                $group_id = $data->id;
                $view_url = "/admin/group_levels?group_id=$group_id";
                return "<a class='disable-outline' href='{$view_url}'>设置等级</a>";
            });

            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

                $filter->equal('subject_id', '所属问卷')->select(function () {
                    return Subject::pluck('title', 'id')->toArray();
                });
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) use ($subject_id) {
                $actions->disableView();
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
        $group = ModelsGroup::with('subject');
        return Show::make($id, $group, function (Show $show) use ($id) {
            $show->field('id');
            $show->field('title');
            $show->field('subject.title', '所属问卷');
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
        return Form::make(new Group(), function (Form $form) {
            $form->display('id');
            $form->text('title');

            // $subject_id = Request::input('subject_id');
            $form->select('subject_id', '选择所属问卷')
                ->options(function() {
                    $subjects = Subject::query()->pluck('title', 'id')->toArray();
                    return $subjects;
                });
        });
    }
}
