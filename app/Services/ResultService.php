<?php
namespace App\Services;

use App\Models\Answer;
use App\Models\Group;
use App\Services\GroupScoreTrait;

class ResultService
{
    use GroupScoreTrait;

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
            if (!$group_id) {
                continue;
            }
            $data[$group_id]['score'] = ($data[$group_id]['score'] ?? 0) + $a->score;
        }

        // 12是Answers表年龄答案的索引id值，暂时写死todo
        $age = $results[12];

        // 1,2是Answers表男孩，女孩答案的索引id值，暂时写死 todo
        $sex = isset($results[1]) ? 1 : (isset($results[2]) ? 0 : -1);

        return $this->calculateGroupLevels($data, $age, $sex);
    }

    private function calculateGroupLevels($group_results, $age, $sex)
    {
        $group_ids = array_keys($group_results);

        $groups = Group::whereIn('id', $group_ids)->get();

        foreach ($groups as $group) {
            $group_results[$group->id]['title'] = $group->title;

            $group_score = $this->getGroupTotalScoreByAgeSex($group->id, $age, $sex);

            // 按5级，取四舍五入
            // 因为分数越低，等级越高，所以用5-
            $group_results[$group->id]['level'] = 5 - round($group_results[$group->id]['score'] * 5/$group_score);
        }

        return $group_results;
    }
}