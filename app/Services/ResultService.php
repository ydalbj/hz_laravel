<?php
namespace App\Services;

use App\Common\AgeHelper;
use App\Models\Answer;
use App\Models\Group;
use App\Models\Question;
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
            // 如果选项设置为`不能干什么`
            // 因为分数越低，等级越高，所以用5-
            // $group_results[$group->id]['level'] = 5 - round($group_results[$group->id]['score'] * 5/$group_score);

            // 如果设置`能干什么`
            // 因为分数越高，等级越高
            $group_results[$group->id]['level'] =  round($group_results[$group->id]['score'] * 5/$group_score);
        }

        return $group_results;
    }

    public function getGroupEvaluations(array $results, int $month_age)
    {
        if (empty($results)) {
            return [];
        }

        $question_ids = array_keys($results);
        $questions = Question::whereIn('id', $question_ids)
            ->with('answers', function ($query) {
                $query->select('id', 'question_id', 'title', 'is_selected_pass');
            })
            ->with('group', function ($query) {
                $query->select('id', 'title', 'is_age_standard');
            })
            ->select('id', 'group_id', 'base_age')
            ->get();

        $data = config('question.default_result');
        foreach ($questions as $q) {
            $group_id = $q->group_id;
            if (!$group_id) {
                continue;
            }

            $answer_ids = $results[$q->id];
            if ($q->group->is_age_standard) {
                $calculated_age = $this->calculateAgeStandardByAnswers($q->answers, $answer_ids, $month_age, $q->base_age);
                if (!isset($calculated_age) || $calculated_age < 0) {
                    // 以上都做不到的情况，不记录年龄
                    continue;
                }

                $data[$group_id]['age_standard'] = $calculated_age;
                $data[$group_id]['age_formatted'] = AgeHelper::monthInt2String($calculated_age);
                $data[$group_id]['score'] = round(100 * $calculated_age / $month_age);
            } else {
                $data[$group_id]['level_standard'] = round(5 * count($answer_ids) / count($q->answers));
                $data[$group_id]['score'] = 100 - round(100 * count($answer_ids) / count($q->answers));
                $data[$group_id]['total'] = count($q->answers);
                $data[$group_id]['selected_count'] = count($answer_ids);
            }
            
            $data[$group_id]['group_name'] = $q->group->title;
        }

        return $data;
    }

    private function calculateAgeStandardByAnswers($answers, $selected_answer_ids, $month_age, $base_age)
    {
        $pass_answers = $answers->whereIn('id', $selected_answer_ids)->where('is_selected_pass', 1);
        // 如果选中了`都不做到`,则不符合年龄标准跳过
        if (!$pass_answers->isEmpty()) {
            return null;
        }

        $calculated_age = $base_age + round(count($selected_answer_ids) * 6 / (count($answers) - 1));

        $max_age = 6 * 12; // 最大年龄6岁
        // 如果计算年龄等于max_age, 且孩子月龄大于等于max_age，则标准为当年年龄
        if ($calculated_age >= $max_age && $month_age >= $max_age) {
            $calculated_age = $month_age;
        }

        return $calculated_age;
    }
}