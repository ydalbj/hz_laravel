<?php

namespace App\Http\Controllers;

use App\Common\AgeHelper;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Result;
use App\Models\Answer;
use App\Models\GroupLevel;
use App\Services\ResultService;
use Log;

class ApiController extends Controller
{
    public function subject(int $id)
    {
        $questions = Question::where('subject_id', $id)
        ->where('is_hide', 0)
        ->orderBy('group_id')
        ->orderBy('base_age', 'desc')
        ->with(['answers' => function ($query) {
            $query->select('id', 'question_id', 'title', 'is_selected_pass');
        }])
        ->get();

        return $questions;
    }

    public function answer(Request $request, $id, ResultService $resultService)
    {
        $results = $request->input('results');
        $basic_info = $request->input('basic');
        if (!$results || !$basic_info) {
            return "非法请求";
        }

        $results = json_decode($results, true);
        $basic_info = json_decode($basic_info, true);
        if (!isset($basic_info['birthday'])) {
            return '生日不对';
        }

        $month_age = AgeHelper::getMonthAgeFromBirthday($basic_info['birthday']);
        if (!isset($month_age)) {
            return '生日格式不正确';
        }

        if ($month_age < 0) {
            return '生日不能大于当前日期';
        }

        // 计算分组结果
        // $group_results = $resultService->getGroupResult($results);
        $group_evaluations = $resultService->getGroupEvaluations($results, $basic_info['age']);

        // 计算结果
        $score = $this->calculateScore($results);

        $result = new Result();
        $result = $this->makeUpBasicInfo2Result($result, $basic_info);
        $result->subject_id = $id;
        $result->score = $score;
        $result->results = json_encode($results);
        // $result->group_results = json_encode($group_results);
        $result->group_evaluations = json_encode($group_evaluations);
        $result->save();
        return $result;
        // return $this->formatResult($result);
    }

    private function makeUpBasicInfo2Result(Result $result, array $basic_info)
    {
        $result->wechat_name = $basic_info['wechat_name'];
        $result->telephone = $basic_info['telephone'];
        $result->sex_string = $basic_info['sex'];
        $result->occupation = $basic_info['occupation'];
        $result->birth_info = $basic_info['birth_info'];
        $result->birth_order = $basic_info['birth_order'];
        $result->who_take_care = $basic_info['who_take_care'];
        $result->region = $basic_info['region'];
        $result->birthday = $basic_info['birthday'];
        $result->birth_situations = json_decode($basic_info['birth_situations'], true);
        $result->group_evaluations = json_decode($basic_info['group_evaluations'], true);

        return $result;
    }

    private function formatResult(Result $result)
    {
        $data = [];
        $data['subject_id'] = $result->subject_id;
        $data['score'] = $result->score;
        $group_results = json_decode($result->group_results, true);

        $group_levels = [];
        $i = 0;
        foreach ($group_results as $group_id => $v) {
            $data['group']['titles'][$i] = $v['title'];
            $data['group']['levels'][$i] = $v['level'];
            $data['group']['scores'][$i] = $v['score'];

            $group_levels[$i]['group_id'] = $group_id;
            $group_levels[$i]['level'] = $v['level'];
            $i++;
        }

        $query = GroupLevel::query();
        $is_first = true;
        $where = 'where';
        foreach ($group_levels as $v) {
            if (!$is_first) {
                $where = 'orWhere';
            }
            $query->{$where}(function ($sub) use ($v) {
                $sub->where('group_id', $v['group_id'])->where('level', $v['level']);
            });

            $is_first = false;
        }

        $results = $query->with(['group' => function ($sub) {
                $sub->select('id', 'title');
            }])
            ->select('group_id', 'evaluation')
            ->get();

        $group_levels_data = [];
        foreach ($results as $k => $v) {
            $group_levels_data[$k]['title'] = $v->group->title;
            $group_levels_data[$k]['evaluation'] = $v->evaluation;
        }

        $data['group_level'] = $group_levels_data;
        return $data;
    }
    
    public function calculateScore(array $results)
    {
        $answer_ids = array_keys($results);

        $score = Answer::whereIn('id', $answer_ids)->sum('score');
        Log::debug('score:' . $score);
        return $score;
    }
}
