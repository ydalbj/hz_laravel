<?php
namespace App\Services;

use App\Models\Answer;
use App\Models\Group;

class ResultService
{
    public function getGroupResult($results)
    {
        if (empty($results)) {
            return [];
        }

        $answers_ids = array_keys($results);

        $answers = Answer::whereIn('id', $answers_ids)
            ->with('question', function ($query) {
                $query->select('id', 'group_id');
            })
            ->select('score', 'question_id')
            ->get();

        $data = [];
        foreach ($answers as $a) {
            $group_id = $a->question->group_id;
            $data[$group_id]['score'] = ($data[$group_id]['score'] ?? 0) + $a->score;
        }

        return $this->calculateGroupLevels($data);
    }

    private function calculateGroupLevels($group_results)
    {
        $group_ids = array_keys($group_results);

        $groups = Group::whereIn('id', $group_ids)->get();

        foreach ($groups as $group) {
            $group_results[$group->id]['title'] = $group->title;

            // 按5级，取四舍五入
            $group_results[$group->id]['level'] = round($group_results[$group->id]['score'] * 5);
        }

        return $group_results;
    }
}