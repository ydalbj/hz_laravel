<?php
namespace App\Services;

use App\Models\Question;
use App\Models\Answer;

trait GroupScoreTrait
{
    public function getGroupTotalScoreByAgeSex(int $group_id, int $age = -1, int $sex = -1)
    {
        $query = Question::where('group_id', $group_id);
            // ->where('type', $single_option);

        if ($age > -1) {
            $query->where(function ($sub_query) use ($age) {
                $sub_query->where('min_age', '-1')->orWhere('min_age', '<=', $age);
            })
            ->where(function ($sub_query) use ($age) {
                $sub_query->where('max_age', '-1')->orWhere('max_age', '>=', $age);
            });
        }

        if ($sex > -1) {
            $query->where(function ($sub_query) use ($sex) {
                $sub_query->where('for_sex', -1)->orWhere('for_sex', $sex);
            });
        }

        $question_ids = $query->pluck('id')->toArray();

        $single_option = 0;
        $single_total_score = Answer::whereIn('question_id', $question_ids)
            ->whereHas('question', function($query) use($single_option) {
                $query->where('type', $single_option);
            })
            ->groupBy('question_id')
            ->selectRaw('MAX(score) AS max_score')
            ->get()
            ->sum('max_score');

        // 计算多选题总得分
        $multiple_option = 1; // 多选题
        $multiple_total_score = Answer::whereIn('question_id', $question_ids)
            ->whereHas('question', function($query) use($multiple_option) {
                $query->where('type', $multiple_option);
            })
            ->sum('score');
        return $multiple_total_score + $single_total_score;
    }
}