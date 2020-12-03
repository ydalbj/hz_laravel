<?php
namespace App\Services;

use App\Models\Answer;
use App\Models\Group;
use App\Models\Question;

class GroupService
{
    /**
     * 更新group状态(question_count, max_score字段),如果指定分组id >0 ,则只更新该分组数据，否则更新主题所有分组数据
     * @param int $subject_id 主题id
     */
    public function updateSubjectGroupsState(int $subject_id)
    {
        $group_ids = Group::where('subject_id', $subject_id)->pluck('id');
        foreach ($group_ids as $group_id) {
            $this->updateGroupState($group_id);
        }

        return;
    }

    /**
     * 更新group状态(question_count, max_score字段),如果指定分组id >0 ,则只更新该分组数据，否则更新主题所有分组数据
     * @param int $group_id 分组id
     */
    public function updateGroupState(int $group_id = -1)
    {
        $group = Group::find($group_id);
        if (!$group) {
            return;
        }

        $question_ids = Question::where('group_id', $group_id)->pluck('id')->toArray();

        $question_count = count($question_ids);

        // 计算多选题总得分
        $multiple_option = 1; // 多选题
        $multiple_total_score = Answer::whereIn('question_id', $question_ids)
            ->whereHas('question', function($query) use($multiple_option) {
                $query->where('type', $multiple_option);
            })
            ->sum('score');
        
        $single_option = 0;
        $single_total_score = Answer::whereIn('question_id', $question_ids)
            ->whereHas('question', function($query) use($single_option) {
                $query->where('type', $single_option);
            })
            ->groupBy('question_id')
            ->selectRaw('MAX(score) AS max_score')
            ->get()
            ->sum('max_score');

        $group->question_count = $question_count;
        $group->max_score = $multiple_total_score + $single_total_score;
        return $group->save();
    }
}